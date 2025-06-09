<?php

namespace App\Http\Controllers;

use App\Library\DigitalSignature;
use App\Library\EAuthentication;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserCertificate;
use App\Rules\UniqueEmail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class EAuthController extends Controller
{
    private string $homeRouteName = 'site.home';
    private string $adminRouteName = 'admin.home';

    const LEGAL_FORM_PERSON = 'person';
    const LEGAL_FORM_COMPANY = 'company';


    /**
     * Starts a eAuth process by open and submit form automatically to Identity provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
     */
    public function login(Request $request, $source = ''): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $eAuth = new EAuthentication();
        return $eAuth->spLoginPage($source);
    }

    /**
     * Integration call this method to send request response to us
     * @param Request $request
     * @param string $source You can use this to detect user type or login form... by sending
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
     */
    public function loginCallback(Request $request, $source = ''): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $eAuth = new EAuthentication();
        Log::channel('eauth')->info('EAuthController::SAMLResponse: ' . $request->input('SAMLResponse'));
        $userInfo = $eAuth->userData($request->input('SAMLResponse'));

        if (isset($userInfo['login_source']) && ($userInfo['login_source'] == "nap_pik" || $userInfo['login_source'] == "noi_pik")) {
            return $this->showMessage($this->homeRouteName, __('eauth.nap_pik'));
        }

        //check if kep exist and sign
        if ( isset($userInfo['certificate']) ) {
            $certInfo = DigitalSignature::getContents($userInfo['certificate']);
            $details = DigitalSignature::getSubjectIdentifier($certInfo);

            if (empty($certInfo) || !isset($certInfo['subject']) && !isset($certInfo['serialNumber'])) {
                return $this->showMessage($this->homeRouteName, 'Невалиден електронен подпис');
            }

            $personIdentity = !isset($certInfo['subject']) || !isset($certInfo['subject']['serialNumber']) ? [] : explode('-', $certInfo['subject']['serialNumber']);
            if(sizeof($personIdentity) != 2 || empty($personIdentity[1])){
                return $this->showMessage($this->homeRouteName, 'Невалидно ЕГН в електронния подпис');
            }
            $personIdentity = $personIdentity[1];
            $companyIdentity = !isset($certInfo['subject']) || !isset($certInfo['subject']['organizationIdentifier']) ? [] : explode('-', $certInfo['subject']['organizationIdentifier']);
            $companyIdentity = sizeof($companyIdentity) != 2 || empty($companyIdentity[1]) ? null : $companyIdentity[1];

            //Check if user with certificate exist and login
            $existCert = UserCertificate::with(['user'])
                ->where('user_type', User::class)
                ->where('certificate_number', '=', $certInfo['serialNumber'])
                ->first();

            if ( $existCert ) {
                // Update IP and is_council_of_minsters
                $existCert->user()->update([
                    'ip'                     => $request->ip(),
                    'is_council_of_minsters' => $companyIdentity == env('COUNCIL_OF_MINSTERS_EIK') && env('COUNCIL_OF_MINSTERS_EIK')
                ]);
                //

                return $this->redirectExistingUser($existCert->user);
            }

            //Log::info(json_encode($userInfo));
            $fullName = mb_convert_case(transliterate_new($certInfo['subject']['CN'], true), MB_CASE_TITLE, 'UTF-8');
            $fullNameExplode = getNamesByFullName($fullName, false);
            $userInfo['first_name'] = $fullNameExplode['first_name'];
            $userInfo['middle_name'] = $fullNameExplode['middle_name'];
            $userInfo['last_name'] = $fullNameExplode['last_name'];
            $userInfo['person_identity'] = $personIdentity;
            $userInfo['company_identity'] = $companyIdentity;
            $userInfo['org_name'] = null;

            //Check if user with this email exist
            $userInfo['email'] = $certInfo['subject']['emailAddress'] ? strtolower($certInfo['subject']['emailAddress']) : '';

            $existUser = User::where('person_identity', '=', $userInfo['person_identity'])->first();

            // If the user doesn't exist, check for a user by e-mail with no person_identity
            if (!$existUser && $userInfo['email']) {
                $existUser = User::whereNull('person_identity')->where('email', $userInfo['email'])->first();

                // If a user with no person_identity is found by the e-mail, update the person identity
                if ($existUser) {
                    $existUser->update([
                        'person_identity' => $userInfo['person_identity']
                    ]);
                }
            }
            //

            if ($existUser) {
                // Update IP and is_council_of_minsters
                $existUser->update([
                    'ip'                     => $request->ip(),
                    'is_council_of_minsters' => $userInfo['company_identity'] == env('COUNCIL_OF_MINSTERS_EIK') && env('COUNCIL_OF_MINSTERS_EIK')
                ]);
                //

                $this->addUserCertificate($existUser, $userInfo['certificate']);
                return $this->redirectExistingUser($existUser);
            }
            //return $this->saveNewUser($userInfo, true);
        }

        $existUser = User::where('person_identity', '=', $userInfo['identity_number'])->first();
        if(isset($existUser) && $existUser) {
            // Update IP and is_council_of_minsters
            $existUser->update([
                'ip'                     => $request->ip(),
                'is_council_of_minsters' => $userInfo['company_identity'] == env('COUNCIL_OF_MINSTERS_EIK') && env('COUNCIL_OF_MINSTERS_EIK')
            ]);
            //

            return $this->redirectExistingUser($existUser, $source);
        }

        $fullNameExplode = getNamesByFullName($userInfo['name'], false);
        $userInfo['first_name'] = $fullNameExplode['first_name'];
        $userInfo['middle_name'] = $fullNameExplode['middle_name'];
        $userInfo['last_name'] = $fullNameExplode['last_name'];
        $userInfo['person_identity'] = $userInfo['legal_form'] == self::LEGAL_FORM_PERSON ? $userInfo['identity_number'] : null;
        $userInfo['company_identity'] = null;
        $userInfo['org_name'] = null;
        $userInfo['source'] = $source;

        return view('eauth.create_user', compact('userInfo'));
    }

    /**
     * When user not exist, and we receive all required data
     * or after user submit missing data, we use this method to create new user in db
     * @param $data
     * @param $createCertificate
     * @return \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
     */
    private function saveNewUser($data, $createCertificate = false): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        DB::beginTransaction();
        try {
            $user = new User([
                'password' => Hash::make(Str::random(8)),
                'user_type' => User::USER_TYPE_EXTERNAL,
                'first_name' => $data['first_name'] ?? null,
                'last_name' =>  $data['last_name'] ?? null,
                'org_name' =>  $data['org_name'] ?? null,
                'username' => $data['email'],
                'email' => $data['email'],
                'notification_email' => $data['email'],
                'is_org' => isset($data['company_identity']) && !empty($data['company_identity']) ? 1 : 0,
                'phone' => $data['phone'] ?? null,
                'activity_status' => User::STATUS_ACTIVE,
                'active' => 1,
                'email_verified_at' => Carbon::now(),
                'last_login_at' => Carbon::now(),
                'person_identity' => $data['person_identity'] ?? null,
                'company_identity' => $data['company_identity'] ?? null,
                'eauth' => 1
            ]);

            if( $user ) {
                $user->save();
            }

            //assign role
            $role = Role::where('name', User::EXTERNAL_USER_DEFAULT_ROLE)->first();
            if( $role ) {
                $user->assignRole($role);
            }

            $user->refresh();

            if($createCertificate){
                $this->addUserCertificate($user, $data['certificate']);
            }

            Auth::login($user);

            \Illuminate\Support\Facades\Session::put('user_last_login', $user->last_login_at);
            $sessionLifetime = Setting::where('name', '=', Setting::SESSION_LIMIT_KEY)->first();
            \Illuminate\Support\Facades\Session::put('user_session_time_limit', $sessionLifetime ? $sessionLifetime->value : config('app.default_session_expiration'));
            DB::commit();
            return redirect(route($this->homeRouteName));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('['.Carbon::now().'] : eAuth Integration save user error. Data ('.json_encode($data).'). Error: '.$e->getMessage());
            return $this->showMessage($this->homeRouteName, __('eauth.unknown_error'));
        }
    }

    /**
     * When user not exist and api do not return user email we need to ask user about his email address.
     * After validation, we create new user
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createUserSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', new UniqueEmail(), 'max:255'],
            'first_name' => ['required', 'string', 'min:3', 'max:255'],
            'middle_name' => ['required', 'string', 'min:3', 'max:255'],
            'last_name' => ['required', 'string', 'min:3', 'max:255'],
            'phone' => ['nullable', 'string', 'min:3', 'max:50'],
            'person_identity' => ['required', 'string', 'max:20'],
            'company_identity' => ['nullable', 'string'],
            'org_name' => ['nullable', 'string', 'max:255']
        ]);

        if( $validator->fails() ) {
            $userInfo = $request->all();
            $validationErrors = $validator->errors()->toArray();
            return view('eauth.create_user', compact('userInfo', 'validationErrors'))->with('danger', __('custom.check_for_errors'));
        }

        $validated = $validator->validated();
        $validated['email'] = !empty($validated['email']) ? strtolower($validated['email']) : $validated['email'];
        return $this->saveNewUser($validated);
    }

    /**
     * We need a public metadata page as service provider for the integration
     * This link page is part of auth request message
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function spMetadata($callback_source = ''): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $eAuth = new EAuthentication();
        return $eAuth->spMetadata($callback_source);
    }

    /**
     * When user exist we sign him and redirect to his role home page
     * @param User $user
     * @return \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
     */
    private function redirectExistingUser(User $user): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $redirectRoute = $user->user_type == User::USER_TYPE_EXTERNAL ? $this->homeRouteName : $this->adminRouteName;
        //Check user status and return message if status not allow login
        if( in_array($user->status, [User::STATUS_INACTIVE, User::STATUS_BLOCKED]) || !$user->active ) {
            $userStatuses = User::getUserStatuses();
            if( !$user->active ) {
                return $this->showMessage($redirectRoute, __('auth.account_not_active'));
            }

            return $this->showMessage($redirectRoute, __('auth.account_restricted', ['status' => $userStatuses[$user->status]]));
        }

        Auth::login($user);
        $user->last_login_at = Carbon::now();
        $user->eauth = 1;
        $user->save();

        if ($user->user_type == User::USER_TYPE_INTERNAL) {
            $redirectRoute = 'admin.home';
        } else{
            $redirectRoute = 'site.home';
        }

        \Illuminate\Support\Facades\Session::put('user_last_login', $user->last_login_at);
        $sessionLifetime = Setting::where('name', '=', Setting::SESSION_LIMIT_KEY)->first();
        \Illuminate\Support\Facades\Session::put('user_session_time_limit', $sessionLifetime ? $sessionLifetime->value : config('app.default_session_expiration'));

        return redirect(route($redirectRoute));
    }

    private function showMessage(string $route, string $msg, string $type = 'error'): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $typeMsg = match ($type) {
            default => 'danger',
        };
        return redirect(route($route))->with($typeMsg, $msg);
    }

    public function logout()
    {
        echo 'logout';
    }

    private function addUserCertificate($existingUser, $certificate): void
    {
        $certInfo = DigitalSignature::getContents($certificate);
        $details = DigitalSignature::getSubjectIdentifier($certInfo);
        $existingUser->certificates()->create([
            'certificate_number' => $certInfo['serialNumber'],
            'valid_from'         => date('Y-m-d H:i:s', $certInfo['validFrom_time_t']),
            'valid_to'           => date('Y-m-d H:i:s', $certInfo['validTo_time_t']),
            'eik'                => $details['field'] == 'eik' ? $details['value'] : null,
            'name'               => $certInfo['subject']['CN'],
            'certificate'        => $certificate
        ]);
    }
}

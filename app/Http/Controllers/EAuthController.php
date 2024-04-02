<?php

namespace App\Http\Controllers;

use App\Library\DigitalSignature;
use App\Library\EAuthentication;
use App\Models\Settings;
use App\Models\User;
use App\Models\UserCertificate;
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
    //required field to register new user
    private array $newUserRequiredFields = ['name', 'legal_form', 'identity_number', 'email'];

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
        $userInfo = $eAuth->userData($request->input('SAMLResponse'));

        //Identity number is required
        if( !isset($userInfo['legal_form']) || !isset($userInfo['identity_number'])
            || is_null($userInfo['legal_form']) || is_null($userInfo['identity_number']) ) {
            return $this->showMessage($this->homeRouteName, __('eauth.identity_number_is_missing'));
        }

        //check if kep exist and sign
        if ( isset($userInfo['certificate']) ) {
            $certInfo = DigitalSignature::getContents($userInfo['certificate']);
            $details = DigitalSignature::getSubjectIdentifier($certInfo);

            if (empty($certInfo) || !isset($certInfo['subject']) && !isset($certInfo['serialNumber'])) {
                return $this->showMessage($this->homeRouteName, 'Невалиден електронен подпис');
            }

            //Check if user with certificate exist and login
            $existCert = UserCertificate::with(['user'])
                ->where('user_type', User::class)
                ->where('certificate_number', '=', $certInfo['serialNumber'])
                ->first();

            if ( $existCert ) {
                return $this->redirectExistingUser($existCert->user);
            }

            //Log::info(json_encode($userInfo));
            $fullNameExplode = explode(' ', $userInfo['name'] ?? $certInfo['subject']['CN']);
            //$names = getNamesByFullName(transliterate($person_name));
            $userInfo['first_name'] = $fullNameExplode[0];
            $userInfo['middle_name'] = $fullNameExplode[1];
            $userInfo['last_name'] = isset($fullNameExplode[2]) ? $fullNameExplode[2] : null;
            $userInfo['identity'] = $userInfo['identity_number'];

            //Check if user with this email exist
            $userInfo['email'] = $certInfo['subject']['emailAddress'] ?? '';
            //Second check if email - if yes sign
            $existUser = User::where('email', '=', $userInfo['email'])->first();


            if ($existUser) {
                $this->addUserCertificate($existUser, $userInfo['certificate'], $certInfo, $details);
                $this->updateExistingUser($existUser, $userInfo);
                return $this->redirectExistingUser($existUser);
            }


            if ( $userInfo['legal_form'] == self::LEGAL_FORM_PERSON ) {
                $existUser = User::where('person_identity', '=', $userInfo['identity_number'])->first();
            } else{
                //TODO If api return eik when legal form is company
                $existUser = User::where('company_identity', '=', $userInfo['identity_number'])->first();
            }


            if ($existUser) {
                $this->addUserCertificate($existUser, $userInfo['certificate']);
                $this->updateExistingUser($existUser, $userInfo);
                return $this->redirectExistingUser($existUser);
            }

            return $this->saveNewUser($userInfo, true);
        }

       //Email is optional
        if( isset($userInfo['email']) && !empty($userInfo['email']) ) {
            //Second check if email and exist - if yes sign
            $existUser = User::where('email', '=', $userInfo['email'])->first();
            if($existUser) {
                $this->updateExistingUser($existUser, $userInfo);
                return $this->redirectExistingUser($existUser);
            }
        }

        //First check if identity already exist - if yes sign
        if ( $userInfo['legal_form'] == self::LEGAL_FORM_PERSON ) {
            $existUser = User::where('person_identity', '=', $userInfo['identity_number'])->first();
        } else{
            //TODO If api return eik when legal form is company
            $existUser = User::where('company_identity', '=', $userInfo['identity_number'])->first();
        }

        if($existUser) {
            $this->updateExistingUser($existUser, $userInfo);
            return $this->redirectExistingUser($existUser);
        }

        //Check if all required fields are filled
        $missingFields = false;
        foreach ($this->newUserRequiredFields as $f) {
            if( !isset($userInfo[$f]) || empty($userInfo[$f]) ) {
                $missingFields = true;
            }
        }

        if( $missingFields ) {
            return view('eauth.create_user', compact('userInfo'));
        }

        return $this->saveNewUser($userInfo);
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

            if( $data['legal_form'] == self::LEGAL_FORM_PERSON ) {
                $name = explode(' ', $data['name']);
                $data['first_name'] = $name[0];
                $data['last_name'] = $name[2] ?? ($name[1] ?? $name[0]);
            } else{
                $data['org_name'] = $data['name'];
            }

            $user = new User([
                'password' => Hash::make(Str::random(8)),
                'user_type' => User::USER_TYPE_EXTERNAL,
                'first_name' => $data['first_name'] ?? null,
                'last_name' =>  $data['last_name'] ?? null,
                'org_name' =>  $data['org_name'] ?? null,
                'username' => $data['email'],
                'email' => $data['email'],
                'is_org' => $data['legal_form'] == 'person' ? 0 : 1,
                'phone' => $data['phone'] ?? null,
                'activity_status' => User::STATUS_ACTIVE,
                'active' => 1,
                'email_verified_at' => Carbon::now(),
                'last_login_at' => Carbon::now()
            ]);

            if( $user ) {
                if( $data['legal_form'] == 'person' ) {
                    $user->person_identity = $data['identity_number'];
                } else {
                    $user->company_identity = $data['identity_number'];
                }
                $user->eauth = 1;
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
            $sessionLifetime = config('app.default_session_expiration');
            \Illuminate\Support\Facades\Session::put('user_session_time_limit', $sessionLifetime);
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
            'email' => ['required', 'email', 'unique:users', 'max:255'],
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'phone' => ['nullable', 'string', 'min:3', 'max:50'],
            'legal_form' => ['required', 'string', Rule::in('person', 'company')],
            'identity_number' => ['required', 'string', 'max:20']
        ]);

        if( $validator->fails() ) {
            $userInfo = $request->all();
            $validationErrors = $validator->errors()->toArray();
            return view('eauth.create_user', compact('userInfo', 'validationErrors'))->with('danger', __('custom.check_for_errors'));
        }

        $validated = $validator->validated();

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
        $sessionLifetime = config('app.default_session_expiration');
        \Illuminate\Support\Facades\Session::put('user_session_time_limit', $sessionLifetime);

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

    private function updateExistingUser($existingUser, $userInfo): void
    {
        if ( $userInfo['legal_form'] == self::LEGAL_FORM_PERSON ) {
            $existingUser->person_identity = $userInfo['identity_number'];
        } else {
            $existingUser->company_identity = $userInfo['identity_number'];
            $existingUser->org_name = $userInfo['name'] ?? '';
        }
        $existingUser->save();
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

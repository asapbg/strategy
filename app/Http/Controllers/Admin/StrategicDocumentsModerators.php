<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUsersRequest;
use App\Http\Requests\UpdateUsersRequest;
use App\Mail\UsersChangePassword;
use App\Models\CustomRole;
use App\Models\StrategicDocuments\Institution;
use App\Models\User;
use App\Models\UserSubscribe;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class StrategicDocumentsModerators extends Controller
{
    use VerifiesEmails;

    /**
     * Display Users Table
     *
     * @param Request $request
     * @return RedirectResponse|View
     */
    public function index(Request $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $active = $request->filled('active') ? $request->get('active') : 1;
        $paginate = $request->get('paginate') ?? User::PAGINATE;

        $roles = Role::whereActive(true)
            ->whereIn('name', [CustomRole::MODERATOR_STRATEGIC_DOCUMENT])
            ->orderBy('display_name', 'asc')
            ->get(['id', 'display_name']);

        $users = User::with(['roles', 'institution', 'institution.translation'])
            ->whereHas('roles', function ($q) {
                $q->where('name', CustomRole::MODERATOR_STRATEGIC_DOCUMENT);
            })
            ->when($name, function ($query, $name) {
                return $query->where('first_name', 'ILIKE', "%$name%")->orWhere('last_name', 'ILIKE', "%$name%");
            })
            ->when($email, function ($query, $email) {
                return $query->where('email', 'ILIKE', "%$email%");
            })
            ->where('active', $active)
            ->orderBy('first_name', 'asc')
            ->orderBy('last_name', 'asc')
            ->paginate($paginate);


        return $this->view('admin.strategic_documents.users.index',
            compact('users', 'roles', 'email')
        );
    }

    /**
     * Show create User form
     *
     * @return View
     */
    public function create()
    {
        if (auth()->user()->cannot('createSd', User::class)) {
            return back()->with('danger', __('messages.no_rights_to_view_content'));
        }

        $roles = Role::whereActive(true)
            ->whereIn('name', [CustomRole::MODERATOR_STRATEGIC_DOCUMENT])
            ->orderBy('display_name', 'asc')->get();
        $rolesRequiredInstitutions = User::ROLES_WITH_INSTITUTION;
        $institutions = Institution::optionsList();

        return $this->view('admin.strategic_documents.users.create', compact('roles', 'rolesRequiredInstitutions', 'institutions'));
    }

    /**
     * Create new User record
     *
     * @param StoreUsersRequest $request
     * @return RedirectResponse
     */
    public function store(StoreUsersRequest $request)
    {
        if (auth()->user()->cannot('createSd', User::class)) {
            return back()->with('danger', __('messages.no_rights_to_view_content'));
        }

        $must_change_password = ($request->filled('must_change_password')) ? true : null;
        $data = $request->except(['_token', 'password_confirmation', 'roles', 'sd']);

        $roles = $request->offsetGet('roles');

        DB::beginTransaction();

        try {

            $user = User::make($data);
            $user->user_type = $data['user_type'] ?? 2;
            if ($must_change_password) {
                $message = trans_choice('custom.users', 1) . " {$data['email']} " . __('messages.created_successfully_m') . ". " . __('messages.email_send');
                Mail::to($data['email'])->send(new UsersChangePassword($user));
            } else {
                $message = trans_choice('custom.users', 1) . " {$data['email']} " . __('messages.created_successfully_m');
                $user->password = bcrypt($data['password']);
                $user->email_verified_at = Carbon::now();
                $user->password_changed_at = Carbon::now();
            }

            $user->save();

            $user->assignRole($roles);

            DB::commit();

            return to_route('admin.sd.users')->with('success', $message);

        } catch (\Exception $e) {

            Log::error($e);

            DB::rollBack();

            return redirect()->back()->withInput($request->all())->with('danger', __('messages.system_error'));
        }

    }

    /**
     * Show edit User form
     *
     * @param User $user
     * @return View
     */
    public function edit(User $user)
    {
        if (auth()->user()->cannot('updateSd', $user)) {
            return back()->with('danger', __('messages.no_rights_to_view_content'));
        }

        $roles = Role::whereActive(true)
            ->whereIn('name', [CustomRole::MODERATOR_STRATEGIC_DOCUMENT])
            ->orderBy('display_name', 'asc')->get();
        $rolesRequiredInstitutions = User::ROLES_WITH_INSTITUTION;
        $institutions = Institution::optionsList();
        return $this->view('admin.strategic_documents.users.edit', compact('user', 'roles', 'rolesRequiredInstitutions', 'institutions'));
    }

    /**
     * Update user data in database
     *
     * @param User $user
     * @param UpdateUsersRequest $request
     * @return RedirectResponse
     */
    public function update(User $user, UpdateUsersRequest $request)
    {
        if (auth()->user()->cannot('updateSd', $user)) {
            return back()->with('danger', __('messages.no_rights_to_view_content'));
        }

        $data = $request->except(['_token', 'roles']);
        $roles = $request->input('roles',[]);
//        $rolesNames = sizeof($roles) ? rolesNames($roles) : [];

        $role = Role::whereActive(true)->whereIn('name', [CustomRole::MODERATOR_STRATEGIC_DOCUMENT])->first()->id;

        DB::beginTransaction();

        try {

//            $user->username = mb_strtoupper($data['username']);
            $user->user_type = $data['user_type'] ?? 2;
            $user->first_name = $data['first_name'];
            $user->middle_name = $data['middle_name'];
            $user->last_name = $data['last_name'];
            $user->email = $data['email'];
            $user->active = $data['active'];
            $user->activity_status = $data['activity_status'];
            $user->institution_id = $data['institution_id'];

            if(sizeof($roles) == 1){
                $user->roles()->detach($role);
                $user->roles()->attach($role);
            } else{
                $user->roles()->detach($role);
            }

            if (!is_null($data['password'])) {
                $user->password = bcrypt($data['password']);
                $user->password_changed_at = Carbon::now();
            }

            $user->save();

            DB::commit();

            return to_route('admin.sd.users')
                ->with('success', trans_choice('custom.users', 1) . " " . __('messages.updated_successfully_m'));

        } catch (\Exception $e) {

            Log::error($e);

            DB::rollBack();

            return to_route('admin.sd.users')->with('danger', __('messages.system_error'));

        }
    }

    /**
     * Delete existing User record
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user)
    {
        if (auth()->user()->cannot('deleteSd', $user)) {
            return back()->with('danger', __('messages.no_rights_to_view_content'));
        }
        try {

            foreach ($user->roles->pluck('id') as $role) {
                $user->removeRole($role);
            }
            $user->delete();

            return to_route('admin.sd.users')
                ->with('success', trans_choice('custom.users', 1) . " " . __('messages.deleted_successfully_m'));
        } catch (\Exception $e) {

            Log::error($e);
            return to_route('admin.sd.users')->with('danger', __('messages.system_error'));

        }
    }

}

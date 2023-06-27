<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUsersRequest;
use App\Http\Requests\UpdateUsersRequest;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use App\Mail\UsersChangePassword;

class  UsersController extends Controller
{
    use VerifiesEmails;

    /**
     * Display Users Table
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        if(!auth()->user()->can('manage.users-roles')) {
            return back()->with('danger', __('messages.no_rights_to_view_content'));
        }

        $name = ($request->filled('name')) ? $request->get('name') : null;
        $username = ($request->filled('username')) ? $request->get('username') : null;
        $email = ($request->filled('email')) ? $request->get('email') : null;
        $role_id = ($request->filled('role_id')) ? $request->get('role_id') : null;
        $active = $request->filled('active') ? $request->get('active') : 1;
        $paginate = $request->filled('paginate') ? $request->get('paginate') : User::PAGINATE;

        $roles = Role::whereActive(true)
            ->orderBy('display_name', 'asc')
            ->get(['id','display_name']);

        //\DB::enableQueryLog();
        $users = User::with(['roles'])
            ->when($role_id, function ($query, $role_id) {
                return $query->whereHas('roles', function ($q) use ($role_id) {
                    $q->where('id', $role_id);
                });
            })
            ->when($name, function ($query, $name) {
                return $query->where('first_name', 'ILIKE', "%$name%")->orWhere('last_name', 'ILIKE', "%$name%");
            })
            ->when($username, function ($query, $username) {
                return $query->where('username', 'ILIKE', "%$username%");
            })
            ->when($email, function ($query, $email) {
                return $query->where('email', 'ILIKE', "%$email%");
            })
            ->where('active', $active)
            ->orderBy('first_name', 'asc')
            ->orderBy('last_name', 'asc')
            ->paginate($paginate);
        //dd(\DB::getQueryLog());


        return $this->view('admin.users.index',
            compact('users', 'roles', 'username', 'email')
        );
    }

    /**
     * Export all user's list in excel
     *
     * @return Excel file
     */
    public function export()
    {
        $users = User::with('roles')->get();

        try {
            return Excel::download(new UsersExport($users), 'users.xlsx');
        }
        catch (Exception $e) {

            Log::error($e);

            return redirect()->back()->with('warning', "Възникна грешка при експортирането, моля опитайте отново");
        }
    }

    /**
     * Show create User form
     *
     * @return Response JSON formatted string
     */
    public function create()
    {
        if(!auth()->user()->can('manage.users-roles')) {
            return back()->with('danger', __('messages.no_rights_to_view_content'));
        }

        $roles = Role::whereActive(true)->orderBy('display_name', 'asc')->get();

        return $this->view('admin.users.create', compact('roles'));
    }

    /**
     * Create new User record
     *
     * @param StoreUsersRequest $request
     * @return RedirectResponse
     */
    public function store(StoreUsersRequest $request)
    {
        $must_change_password = ($request->filled('must_change_password')) ? true : null;
        $data = $request->except(['_token','password_confirmation','roles']);
        $roles = $request->offsetGet('roles');

        DB::beginTransaction();

        try {

            $user = User::make($data);
            if ($must_change_password) {
                $message = trans_choice('custom.users', 1)." {$data['username']} ".__('messages.created_successfully_m').". ".__('messages.email_send');
                Mail::to($data['email'])->send(new UsersChangePassword($user));
            } else {
                $message = trans_choice('custom.users', 1)." {$data['username']} ".__('messages.created_successfully_m');
                $user->password = bcrypt($data['password']);
                $user->email_verified_at = Carbon::now();
                $user->password_changed_at = Carbon::now();
            }

            $user->save();

            $user->assignRole($roles);

            DB::commit();

            return to_route('admin.users')->with('success', $message);

        } catch (\Exception $e) {

            Log::error($e);

            DB::rollBack();

            return redirect()->back()->withInput($request->all())->with('danger', __('messages.system_error'));
        }

    }

    /**
     * Show edit User form
     *
     * @param  User  $user
     * @return View
     */
    public function edit(User $user)
    {
        if(!auth()->user()->can('manage.users-roles')) {
            return back()->with('danger', __('messages.no_rights_to_view_content'));
        }

        $roles = Role::whereActive(true)->orderBy('display_name', 'asc')->get();

        return $this->view('admin.users.edit', compact('user', 'roles'));
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
        $data = $request->except(['_token','roles']);
        $roles = $request->offsetGet('roles');

        DB::beginTransaction();

        try {

            $user->username = mb_strtoupper($data['username']);
            $user->first_name = $data['first_name'];
            $user->middle_name = $data['middle_name'];
            $user->last_name = $data['last_name'];
            $user->email = $data['email'];
            $user->active = $data['active'];
            $user->activity_status = $data['activity_status'];

            $user->syncRoles($roles);

            if (!is_null($data['password'])) {
                $user->password = bcrypt($data['password']);
                $user->password_changed_at = Carbon::now();
            }

            $user->save();

            DB::commit();

            return to_route('admin.users')
                ->with('success', trans_choice('custom.users', 1)." ".__('messages.updated_successfully_m'));

        } catch (\Exception $e) {

            Log::error($e);

            DB::rollBack();

            return to_route('admin.users')->with('danger', __('messages.system_error'));

        }
    }

    /**
     * Show edit own profile form
     *
     * @param User $user
     * @return View
     */
    public function editProfile(User $user)
    {
        return $this->view('admin.users.edit-profile', compact('user'));
    }

    /**
     * Update own profile data
     *
     * @param User $user
     * @param UpdateUsersRequest $request
     * @return RedirectResponse
     */
    public function updateProfile(User $user, UpdateUsersRequest $request)
    {
        $data = $request->except(['_token']);

        try {

            $user->first_name = $data['first_name'];
            $user->middle_name = $data['middle_name'];
            $user->last_name = $data['last_name'];
            $user->email = $data['email'];

            if (!is_null($data['password'])) {
                $user->password = bcrypt($data['password']);
                $user->password_changed_at = Carbon::now();
            }

            $user->save();

            auth()->setUser($user);

            return redirect()->back()->with('success', "Вашият профил беше обновен успешно");

        } catch (Exception $e) {

            Log::error($e);

            return redirect()->back()->with('danger', __('messages.system_error'));

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
        try {

            foreach ($user->roles->pluck('id') as $role) {
                $user->removeRole($role);
            }
            $user->delete();

            return to_route('admin.users')
                ->with('success', trans_choice('custom.users', 1)." ".__('messages.deleted_successfully_m'));
        }
        catch (\Exception $e) {

            Log::error($e);
            return to_route('admin.users')->with('danger', __('messages.system_error'));

        }
    }
}

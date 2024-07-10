<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomRole;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsController extends Controller
{
    /**
     * Display a listing of user's roles and permissions
     *
     * @return View
     */
    public function index()
    {
        $roles = Role::whereActive(true)
            ->where('name', '<>', CustomRole::SUPER_USER_ROLE)
            ->where('name', '<>', CustomRole::SANCTUM_USER_ROLE)
            ->get();
//        $perms = Permission::with('roles')->get();
        $perms = Permission::with('roles')->orderBy('id', 'asc')->get();
        return $this->view('admin.permissions.index', compact('roles', 'perms'));
    }
    /**
     * Show create Permission form
     *
     * @return Response JSON formatted string
     */
    public function create()
    {
        if(auth()->user()->cannot('editPermissions', CustomRole::class)) {
            return back()->with('danger', __('messages.no_rights_to_view_content'));
        }

        if(!auth()->user()->can('manage.roles-permissions')) {
            return back()->with('danger', 'Нямате достъп до тази функционалност. Моля свържете се с администратор.');
        }

        return $this->view('admin.permissions.create');
    }

    /**
     * Store a newly created user's role permission.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        if(auth()->user()->cannot('editPermissions', CustomRole::class)) {
            return back()->with('danger', __('messages.no_rights_to_view_content'));
        }

        $request->validate([
            'name' => 'required|min:2',
            'display_name' => 'required|min:2',
        ]);

        $role = Role::whereName('admin')->first();

        try {

            $perm = Permission::create([
                'name' => $request->offsetGet('name'),
                'display_name' => $request->offsetGet('display_name'),
                'guard_name' => config('auth.defaults.guard'),
            ]);

            if ($perm && $role) {
                $role->givePermissionTo($perm);
            }

            $permissions = config('permissions');
            $new_perm = array_merge($permissions, [$perm->name => $perm->display_name]);

            file_put_contents(config_path('permissions.php'), '<?php return ' . var_export($new_perm, true) . ';');

            return to_route('admin.permissions')
                ->with('success', $request->offsetGet('display_name')." ".__('messages.updated_successfully_n'));
        }
        catch (Exception $e) {

            Log::error($e);

            return to_route('admin.permissions')->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Show the form for editing user's role permissions.
     *
     * @param Permission $permission
     * @return View
     */
    public function edit(Permission $permission)
    {
        if(auth()->user()->cannot('editPermissions', CustomRole::class)) {
            return back()->with('danger', __('messages.no_rights_to_view_content'));
        }

        return $this->view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update a user's role permission.
     *
     * @param Request $request
     * @param  permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        if(auth()->user()->cannot('editPermissions', CustomRole::class)) {
            return back()->with('danger', __('messages.no_rights_to_view_content'));
        }

        $request->validate([
            'name' => 'required|min:3'
        ]);
        try {
            $permission->update([
                'name' => $request->offsetGet('name'),
                'display_name' => $request->offsetGet('display_name')
            ]);

            return to_route('admin.permissions')->with('success', $request->offsetGet('display_name')." ".__('messages.updated_successfully_n'));
        }
        catch (Exception $e) {

            Log::error($e);

            return to_route('admin.permissions')->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Remove a user's role permission.
     *
     * @param Permission $permission
     */
    public function destroy(Permission $permission)
    {
        if(auth()->user()->cannot('editPermissions', CustomRole::class)) {
            return back()->with('danger', __('messages.no_rights_to_view_content'));
        }

        try {
            $users_count = DB::table('role_has_permissions')
                ->where('permission_id', $permission->id)
                ->count();

            if($users_count > 0) {
                return back()->with('danger', 'Правото не може да бъде изтрито, тъй като има потребители, които го използват.');
            }

            $permission->delete();

            $name = $permission->display_name ? $permission->display_name : $permission->name;

            return to_route('admin.permissions')->with('success', $name." ".__('messages.deleted_successfully_n'));
        }
        catch (Exception $e) {

            Log::error($e);

            return to_route('admin.permissions')->with('danger', __('messages.system_error'));

        }
    }

    /**
     * Update user's role permission
     *
     * @return JsonResponse
     */
    public function rolesPermissions() {
        if(auth()->user()->cannot('editPermissions', CustomRole::class)) {
            return back()->with('danger', __('messages.no_rights_to_view_content'));
        }

        $res = [];

        try {
            # If params not provided, throw exception
            foreach(['role', 'permission', 'has'] as $field) {
                if (!request()->filled($field)) {
                    throw new AppException('Невалидни параметри');
                }
            }

            # Get Role & Permission
            $role = Role::find(request()->get('role'));
            $perm = Permission::find(request()->get('permission'));

            # If ro or perm. not found, throw exception
            if (!$role || !$perm) {
                throw new AppException('Невалидни параметри');
            }

            # Check if role has Permission
            $roleHasPerm = $role->hasPermissionTo($perm->name);
            # Whether we add or remove permission to role
            $assign = request()->get('has');

            # If we remove permission and the role has the permission, the remove it
            if ($assign == 0 && $roleHasPerm) {
                $role->revokePermissionTo($perm);
            }

            # If we add permission and the role dont have it, the assign it
            if ($assign == 1 && !$roleHasPerm) {
                $role->givePermissionTo($perm);
            }

            $res['success'] = 1;
            $res['error'] = 0;

        } catch (AppException $e) {

            $res['error'] = 1;
            $res['msg'] = $e->getMessage();

        } catch (Exception $e) {

            Log::error($e);

            $res['error'] = 1;
            $res['success'] = 0;
            $res['msg'] = 'Грешка в системата, опитайте по-късно';
        }
        return response()->json($res);
    }

}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    /**
     * Roles that are protected from edits and deletion.
     */
    private const PROTECTED_ROLES = [
        'Super Admin',
    ];

    /**
     * Display a listing of the roles.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $roles = Role::withCount('permissions')
            ->withCount('users')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('roles.index', compact('roles', 'search'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissionGroups = $this->permissionGroups();

        return view('roles.create', compact('permissionGroups'));
    }

    /**
     * Store a newly created role.
     */
    public function store(StoreRoleRequest $request)
    {
        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($request->permissions ?? []);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        $permissionGroups = $this->permissionGroups($role);

        return view('roles.show', compact('role', 'permissionGroups'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        $this->ensureEditable($role);

        $permissionGroups = $this->permissionGroups($role);

        return view('roles.edit', compact('role', 'permissionGroups'));
    }

    /**
     * Update the specified role.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $this->ensureEditable($role);

        $role->update([
            'name' => $request->name,
        ]);

        $role->syncPermissions($request->permissions ?? []);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role)
    {
        $this->ensureEditable($role);

        if ($role->users()->exists()) {
            return back()->with('error', 'Cannot delete a role that is assigned to users.');
        }

        $role->permissions()->detach();
        $role->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Abort when the role is protected (e.g. Super Admin).
     */
    private function ensureEditable(Role $role): void
    {
        abort_if(in_array($role->name, self::PROTECTED_ROLES, true), 403, 'This role is protected.');
    }

    /**
     * All permissions grouped by module, with the given role's permissions flagged.
     *
     * @return \Illuminate\Support\Collection
     */
    private function permissionGroups(?Role $role = null)
    {
        $groups = Permission::orderBy('name')
            ->get()
            ->groupBy(fn ($permission) => Str::before($permission->name, '.'));

        return $groups->map(function ($permissions) use ($role) {
            return $permissions->map(function (Permission $permission) use ($role) {
                return [
                    'name' => $permission->name,
                    'checked' => $role?->hasPermissionTo($permission) ?? false,
                ];
            });
        });
    }
}

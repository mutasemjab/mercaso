<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Gate;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('role-table')) {
            return redirect()->back()->with('error', __('messages.access_denied'));
        }

        if ($request->search) {
            $data = Role::where(function ($query) use ($request) {
                $query->where('roles.name', 'LIKE', "%$request->search%")
                    ->orWhere('roles.guard_name', 'LIKE', "%$request->search%");
            })->paginate(10);
        } else {
            $data = Role::paginate(10);
        }

        return view('admin.roles.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('role-add')) {
            return redirect()->back()->with('error', __('messages.access_denied'));
        }

        $permissions = $this->getGroupedPermissions();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('role-add')) {
            return redirect()->back()->with('error', __('messages.access_denied'));
        }

        $request->validate([
            'name' => 'required|unique:roles,name',
            'perms' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                "name" => $request->name,
                "guard_name" => 'admin',
            ]);

            $role->syncPermissions($request->perms);

            DB::commit();
            return redirect()->route('admin.role.index')->with('success', __('messages.success'));
        } catch (Exception $e) {
            Log::error('Role creation failed: ' . $e->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors(__('messages.error_occurred'))->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (!auth()->user()->can('role-edit')) {
            return redirect()->back()->with('error', __('messages.access_denied'));
        }

        $role = Role::findOrFail($id);
        $permissions = $this->getGroupedPermissions();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('permissions', 'rolePermissions', 'role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('role-edit')) {
            return redirect()->back()->with('error', __('messages.access_denied'));
        }

        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'perms' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::findOrFail($id);
            $role->update([
                'name' => $request->name,
                'guard_name' => 'admin',
            ]);

            $role->syncPermissions($request->perms);

            DB::commit();
            return redirect()->route('admin.role.index')->with('success', __('messages.success'));
        } catch (Exception $e) {
            Log::error('Role update failed: ' . $e->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors(__('messages.error_occurred'))->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        if (!auth()->user()->can('role-delete')) {
            return response()->json(['error' => __('messages.access_denied')], 403);
        }

        try {
            $role = Role::findOrFail($request->id);
            $role->delete();
            return response()->json(['success' => __('messages.deleted_successfully')]);
        } catch (Exception $e) {
            Log::error('Role deletion failed: ' . $e->getMessage());
            return response()->json(['error' => __('messages.error_occurred')], 500);
        }
    }

    /**
     * Group permissions by module for better UI
     */
    private function getGroupedPermissions()
    {
        $permissions = Permission::where('guard_name', 'admin')->get();
        $grouped = [];

        foreach ($permissions as $permission) {
            $parts = explode('-', $permission->name);
            $module = $parts[0];
            $action = $parts[1] ?? 'unknown';

            if (!isset($grouped[$module])) {
                $grouped[$module] = [];
            }

            $grouped[$module][] = [
                'id' => $permission->id,
                'name' => $permission->name,
                'action' => $action,
                'display_name' => __('permissions.' . $permission->name)
            ];
        }

        return $grouped;
    }
}

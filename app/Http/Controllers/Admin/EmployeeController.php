<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('employee-table')) {
            return redirect()->back()->with('error', __('messages.access_denied'));
        }

        $data = Admin::where('is_super_admin', 0);
        
        if ($request->search) {
            $data->where(function ($query) use ($request) {
                $query->where('admins.name', 'LIKE', "%$request->search%")
                    ->orWhere('admins.email', 'LIKE', "%$request->search%")
                    ->orWhere('admins.phone', 'LIKE', "%$request->search%");
            });
        }
        
        $data = $data->paginate(10);
        return view('admin.employee.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('employee-add')) {
            return redirect()->back()->with('error', __('messages.access_denied'));
        }

        $roles = Role::where('guard_name', 'admin')->get();
        return view('admin.employee.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('employee-add')) {
            return redirect()->back()->with('error', __('messages.access_denied'));
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6',
            'roles' => 'required|array'
        ]);

        DB::beginTransaction();
        try {
            $admin = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'phone' => $request->phone,
                'is_super_admin' => 0,
                'password' => Hash::make($request->password),
            ]);

            $admin->assignRole($request->roles);

            DB::commit();
            return redirect()->route('admin.employee.index')
                ->with('success', __('messages.employee_created_successfully'));
        } catch (Exception $e) {
            DB::rollBack();
           Log::error("Employee creation failed: " . $e->getMessage());
            return redirect()->back()
                ->with('error', __('messages.error_occurred'))
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (!auth()->user()->can('employee-edit')) {
            return redirect()->back()->with('error', __('messages.access_denied'));
        }

        $admin = Admin::findOrFail($id);
        $roles = Role::where('guard_name', 'admin')->get();
        $adminRoles = $admin->roles->pluck('id')->toArray();

        return view('admin.employee.edit', compact('admin', 'roles', 'adminRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('employee-edit')) {
            return redirect()->back()->with('error', __('messages.access_denied'));
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $id,
            'password' => 'nullable|min:6',
            'roles' => 'required|array'
        ]);

        DB::beginTransaction();
        try {
            $admin = Admin::findOrFail($id);

            $admin->update([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'phone' => $request->phone,
            ]);

            if ($request->password) {
                $admin->update(['password' => Hash::make($request->password)]);
            }

            $admin->syncRoles($request->roles);

            DB::commit();
            return redirect()->route('admin.employee.index')
                ->with('success', __('messages.employee_updated_successfully'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Employee update failed: " . $e->getMessage());
            return redirect()->back()
                ->with('error', __('messages.error_occurred'))
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('employee-delete')) {
            return redirect()->back()->with('error', __('messages.access_denied'));
        }

        DB::beginTransaction();
        try {
            $admin = Admin::findOrFail($id);
            $admin->delete();

            DB::commit();
            return redirect()->route('admin.employee.index')
                ->with('success', __('messages.employee_deleted_successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Log::error("Employee deletion failed: " . $e->getMessage());
            return redirect()->back()
                ->with('error', __('messages.error_occurred'));
        }
    }

    /**
     * Show employee details (alternative to destroy for viewing)
     */
    public function show($id)
    {
        if (!auth()->user()->can('employee-table')) {
            return redirect()->back()->with('error', __('messages.access_denied'));
        }

        $admin = Admin::with('roles')->findOrFail($id);
        return view('admin.employee.show', compact('admin'));
    }
}
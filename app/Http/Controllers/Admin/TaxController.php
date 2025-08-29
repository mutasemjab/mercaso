<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{

    public function __construct()
    {
        // Define permission mappings for each method
        $this->middleware('auth:admin');
        
        $this->middleware(function ($request, $next) {
            $this->checkPermissions();
            return $next($request);
        });
    }

    private function checkPermissions()
    {
        $action = request()->route()->getActionMethod();
        $resource = 'tax'; // or auto-detect from class name
        
        $permissions = [
            'index'   => $resource . '-table',
            'show'    => $resource . '-table', 
            'create'  => $resource . '-add',
            'store'   => $resource . '-add',
            'edit'    => $resource . '-edit',
            'update'  => $resource . '-edit',
            'destroy' => $resource . '-delete',
        ];

        if (isset($permissions[$action])) {
            if (!auth()->user()->can($permissions[$action])) {
                abort(403, __('messages.access_denied'));
            }
        }
    }


    public function index()
    {
        $taxes = Tax::all();
        return view('admin.taxes.index', compact('taxes'));
    }

    public function create()
    {
        return view('admin.taxes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:0'
        ]);

        Tax::create([
            'name' => $request->name,
            'value' => $request->value
        ]);

        return redirect()->route('taxes.index')->with('success', 'Tax created successfully.');
    }

    public function edit(Tax $tax)
    {
        return view('admin.taxes.edit', compact('tax'));
    }

    public function update(Request $request, Tax $tax)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:0'
        ]);

        $tax->update([
            'name' => $request->name,
            'value' => $request->value
        ]);

        return redirect()->route('taxes.index')->with('success', 'Tax updated successfully.');
    }

}   
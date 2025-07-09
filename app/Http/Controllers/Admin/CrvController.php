<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use Illuminate\Http\Request;
use App\Models\Crv;

class CrvController extends Controller
{
    public function index()
    {
        $crvs = Crv::all();
        return view('admin.crvs.index', compact('crvs'));
    }

    public function create()
    {
        return view('admin.crvs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:0'
        ]);

        Crv::create([
            'name' => $request->name,
            'value' => $request->value
        ]);

        return redirect()->route('crvs.index')->with('success', 'CRV created successfully.');
    }

    public function edit(Crv $crv)
    {
        return view('admin.crvs.edit', compact('crv'));
    }

    public function update(Request $request, Crv $crv)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:0'
        ]);

        $crv->update([
            'name' => $request->name,
            'value' => $request->value
        ]);

        return redirect()->route('crvs.index')->with('success', 'CRV updated successfully.');
    }

} 
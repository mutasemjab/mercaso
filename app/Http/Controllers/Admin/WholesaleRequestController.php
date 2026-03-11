<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\WholesaleRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WholesaleRequestController extends Controller
{
    public function index()
    {
        $data = WholesaleRequest::where('status', 1)
            ->with('user')
            ->latest()
            ->paginate(PAGINATION_COUNT);

        return view('admin.wholeSales.requests', compact('data'));
    }

    public function approve($id)
    {
        $request = WholesaleRequest::findOrFail($id);
        $request->status = 2; // approved
        $request->save();

        // Change user type to wholesale
        $user = User::findOrFail($request->user_id);
        $user->user_type = 2;
        $user->save();

        return redirect()->route('admin.wholesaleRequest.index')
            ->with(['success' => 'Wholesale request approved successfully']);
    }

    public function reject($id)
    {
        $request = WholesaleRequest::findOrFail($id);
        $request->status = 3; // rejected
        $request->save();

        return redirect()->route('admin.wholesaleRequest.index')
            ->with(['success' => 'Wholesale request rejected']);
    }
}

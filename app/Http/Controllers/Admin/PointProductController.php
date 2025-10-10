<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PointProduct;
use App\Models\PointProductPurchase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PointProductController extends Controller
{


    /**
     * Display user's purchase history.
     */
    public function purchases(Request $request)
   {
    $query = PointProductPurchase::with(['user', 'pointProduct']);
    
    // Apply filters
    if ($request->filled('product')) {
        $query->where('point_product_id', $request->product);
    }
    
    if ($request->filled('date_from')) {
        $query->whereDate('purchased_at', '>=', $request->date_from);
    }
    
    if ($request->filled('date_to')) {
        $query->whereDate('purchased_at', '<=', $request->date_to);
    }
    
    if ($request->filled('user_search')) {
        $search = $request->user_search;
        $query->whereHas('user', function($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%");
        });
    }
    
    // Get purchases with pagination
    $purchases = $query->orderBy('purchased_at', 'desc')->paginate(15);
    
    // Calculate statistics
    $totalPurchases = PointProductPurchase::count();
    $totalItemsSold = PointProductPurchase::sum('quantity');
    $totalPointsSpent = PointProductPurchase::sum('points_spent');
    $uniqueBuyers = PointProductPurchase::distinct('user_id')->count();
    
    // Get all products for filter dropdown
    $products = PointProduct::orderBy('name')->get();
    
    return view('admin.point-products.purchases', compact(
        'purchases',
        'products',
        'totalPurchases',
        'totalItemsSold',
        'totalPointsSpent',
        'uniqueBuyers'
    ));
}

    /**
     * Admin: Display all point products for management.
     */
    public function index()
    {
        $pointProducts = PointProduct::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.point-products.index', compact('pointProducts'));
    }

    /**
     * Admin: Show create form.
     */
    public function create()
    {
        return view('admin.point-products.create');
    }

    /**
     * Admin: Store new point product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'points_required' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] =uploadImage('assets/admin/uploads', $request->image);
        }

        PointProduct::create($data);

        return redirect()->route('point-products.index')
                       ->with('success', 'Point product created successfully!');
    }

    /**
     * Admin: Show edit form.
     */
    public function edit(PointProduct $pointProduct)
    {
        return view('admin.point-products.edit', compact('pointProduct'));
    }

    /**
     * Admin: Update point product.
     */
    public function update(Request $request, PointProduct $pointProduct)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'points_required' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = uploadImage('assets/admin/uploads', $request->image);
        }

        $pointProduct->update($data);

        return redirect()->route('point-products.index')
                       ->with('success', 'Point product updated successfully!');
    }

    /**
     * Admin: Delete point product.
     */
    public function destroy(PointProduct $pointProduct)
    {
        $pointProduct->delete();
        return redirect()->route('point-products.index')
                       ->with('success', 'Point product deleted successfully!');
    }
}
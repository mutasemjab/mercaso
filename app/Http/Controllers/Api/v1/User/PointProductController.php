<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\PointProduct;
use App\Models\PointProductPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PointProductController extends Controller
{
    /**
     * Get all available point products for authenticated user.
     */
    public function index()
    {
        $user = Auth::user();
        
        $pointProducts = PointProduct::where('is_active', true)
                                   ->where('stock', '>', 0)
                                   ->orderBy('points_required')
                                   ->get()
                                   ->map(function($product) use ($user) {
                                       return [
                                           'id' => $product->id,
                                           'name' => $product->name,
                                           'description' => $product->description,
                                           'image' => $product->image ? asset('storage/' . $product->image) : null,
                                           'points_required' => $product->points_required,
                                           'stock' => $product->stock,
                                           'can_afford' => $product->canUserAfford($user),
                                           'is_available' => $product->isAvailable()
                                       ];
                                   });

        return response()->json([
            'success' => true,
            'data' => [
                'user_points' => $user->points,
                'point_products' => $pointProducts
            ]
        ]);
    }

    /**
     * Get point products user can afford.
     */
    public function affordable()
    {
        $user = Auth::user();
        
        $pointProducts = PointProduct::affordableFor($user)
                                   ->orderBy('points_required')
                                   ->get()
                                   ->map(function($product) {
                                       return [
                                           'id' => $product->id,
                                           'name' => $product->name,
                                           'description' => $product->description,
                                           'image' => $product->image ? asset('storage/' . $product->image) : null,
                                           'points_required' => $product->points_required,
                                           'stock' => $product->stock
                                       ];
                                   });

        return response()->json([
            'success' => true,
            'data' => [
                'user_points' => $user->points,
                'affordable_products' => $pointProducts
            ]
        ]);
    }

    /**
     * Get specific point product details.
     */
    public function show($id)
    {
        $user = Auth::user();
        $pointProduct = PointProduct::find($id);

        if (!$pointProduct) {
            return response()->json([
                'success' => false,
                'message' => 'Point product not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pointProduct->id,
                'name' => $pointProduct->name,
                'description' => $pointProduct->description,
                'image' => $pointProduct->image ? asset('storage/' . $pointProduct->image) : null,
                'points_required' => $pointProduct->points_required,
                'stock' => $pointProduct->stock,
                'is_available' => $pointProduct->isAvailable(),
                'can_afford' => $pointProduct->canUserAfford($user),
                'user_points' => $user->points
            ]
        ]);
    }

    /**
     * Purchase a point product via API.
     */
    public function purchase(Request $request)
    {
        $request->validate([
            'point_product_id' => 'required|exists:point_products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $pointProduct = PointProduct::findOrFail($request->point_product_id);
        $quantity = $request->quantity;
        $totalPointsRequired = $pointProduct->points_required * $quantity;

        // Check if product is available
        if (!$pointProduct->isAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'This product is not available.'
            ], 400);
        }

        // Check if user has enough points
        if ($user->points < $totalPointsRequired) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient points. You need ' . $totalPointsRequired . ' points but only have ' . $user->points . ' points.'
            ], 400);
        }

        // Check if there's enough stock
        if ($pointProduct->stock < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available. Only ' . $pointProduct->stock . ' items left.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Deduct points from user
            $user->decrement('points', $totalPointsRequired);

            // Reduce stock
            $pointProduct->decrement('stock', $quantity);

            // Create purchase record
            $purchase = PointProductPurchase::create([
                'user_id' => $user->id,
                'point_product_id' => $pointProduct->id,
                'points_spent' => $totalPointsRequired,
                'quantity' => $quantity,
                'purchased_at' => now()
            ]);

            DB::commit();

            // Refresh user to get updated points
            $user->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Purchase successful!',
                'data' => [
                    'purchase_id' => $purchase->id,
                    'product_name' => $pointProduct->name,
                    'quantity' => $quantity,
                    'points_spent' => $totalPointsRequired,
                    'remaining_points' => $user->points,
                    'purchased_at' => $purchase->purchased_at->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Purchase failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Get user's purchase history.
     */
    public function purchases()
    {
        $user = Auth::user();
        
        $purchases = PointProductPurchase::with('pointProduct')
                                       ->where('user_id', $user->id)
                                       ->orderBy('purchased_at', 'desc')
                                       ->paginate(20);

        $purchaseData = $purchases->map(function($purchase) {
            return [
                'id' => $purchase->id,
                'product_name' => $purchase->pointProduct->name,
                'product_image' => $purchase->pointProduct->image ? asset('storage/' . $purchase->pointProduct->image) : null,
                'quantity' => $purchase->quantity,
                'points_spent' => $purchase->points_spent,
                'purchased_at' => $purchase->purchased_at->format('Y-m-d H:i:s')
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'current_page' => $purchases->currentPage(),
                'total_pages' => $purchases->lastPage(),
                'total_purchases' => $purchases->total(),
                'purchases' => $purchaseData
            ]
        ]);
    }

}
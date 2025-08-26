<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{

    public function index()
    {
        $data = Brand::get();
        return response()->json(['data'=>$data]);

    }

      public function getBrandProduct($id, Request $request)
    {
        $token = $request->bearerToken();
        $authenticatedUser = null;

        if ($token) {
            $authenticatedUser = Auth::guard('user-api')->user();
        }

        // Determine which product types to show based on user type
        $productTypesToShow = [3]; // Always show "both" type products
        
        if ($authenticatedUser) {
            if ($authenticatedUser->user_type == 1) {
                // Retail user: show retail (1) and both (3)
                $productTypesToShow[] = 1;
            } elseif ($authenticatedUser->user_type == 2) {
                // Wholesale user: show wholesale (2) and both (3)
                $productTypesToShow[] = 2;
            }
        } else {
            // Non-authenticated users: show retail (1) and both (3)
            $productTypesToShow[] = 1;
        }

        // Load the brand with its products filtered by product_type and related data
        $brand = Brand::with([
            'products' => function ($query) use ($productTypesToShow) {
                $query->whereIn('product_type', $productTypesToShow);
            },
            'products.productImages',
            'products.productReviews',
            'products.variations',
            'products.units',
            'products.unit',
            'products.offers',
        ])->find($id);

        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }

        // Prepare the response data
        $response = [
            'brand' => [
                'name' => $brand->name,
                'photo' => $brand->photo,
            ],
            'products' => $this->formatProducts($brand->products, $authenticatedUser)
        ];

        return response()->json(['data' => $response]);
    }

    private function formatProducts($products, $authenticatedUser)
    {
        $formattedProducts = [];

        foreach ($products as $item) {
            if ($authenticatedUser) {
                $userType = $authenticatedUser->user_type;

                // Filter product data based on user_type and product_type
                if ($userType == 1) { // Retail user
                    // For retail users, show retail pricing even for "both" type products
                    $item->unit_name = $item->unit ? $item->unit->name_ar : null;
                    $item->price = $item->selling_price_for_user;
                    $item->quantity = $item->available_quantity_for_user ?? 0;
                } elseif ($userType == 2) { // Wholesale user
                    // For wholesale users, show wholesale pricing
                    if ($item->product_type == 2 || $item->product_type == 3) {
                        // Wholesale or both - show wholesale data
                        $unit = $item->units->first();
                        $item->unit_name = $unit ? $unit->name_ar : null;
                        $item->price = $unit ? $unit->pivot->selling_price : null;
                        $item->quantity = $item->available_quantity_for_wholeSale ?? 0;
                    } else {
                        // Should not reach here due to filtering, but fallback to retail data
                        $item->unit_name = $item->unit ? $item->unit->name_ar : null;
                        $item->price = $item->selling_price_for_user;
                        $item->quantity = $item->available_quantity_for_user ?? 0;
                    }
                }

                // Check if the product is a favorite for the authenticated user
                $item->is_favourite = $authenticatedUser->favourites()->where('product_id', $item->id)->exists();

                // Filter offers by user_type
                $item->has_offer = $item->offers()->where('user_type', $userType)->exists();
                $item->offer_id = $item->has_offer ? $item->offers()->where('user_type', $userType)->first()->id : 0;
                $item->offer_price = $item->has_offer ? $item->offers()->where('user_type', $userType)->first()->price : 0;

            } else {
                // Default data for non-authenticated users (treat as retail)
                $item->unit_name = $item->unit ? $item->unit->name_ar : null;
                $item->price = $item->selling_price_for_user;
                $item->quantity = $item->available_quantity_for_user ?? 0;
                $item->is_favourite = false;

                // Default offers for non-authenticated users (assume user_type = 1)
                $item->has_offer = $item->offers()->where('user_type', 1)->exists();
                $item->offer_id = $item->has_offer ? $item->offers()->where('user_type', 1)->first()->id : 0;
                $item->offer_price = $item->has_offer ? $item->offers()->where('user_type', 1)->first()->price : 0;
            }

            // Set additional product details
            $item->rating = $item->rating;
            $item->total_rating = $item->total_rating;

            $formattedProducts[] = $item;
        }

        return $formattedProducts;
    }



}

<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SubCategoryResource;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $token = $request->bearerToken();
        $authenticatedUser = null;
    
        if ($token) {
            $authenticatedUser = Auth::guard('user-api')->user();
        }
    
        if ($authenticatedUser) {
            // Get the country ID of the authenticated user
            $countryId = $authenticatedUser->country_id;
    
            // Retrieve categories that are associated with the user's country
            $categories = Category::whereNull('category_id')
                ->whereHas('countries', function ($query) use ($countryId) {
                    $query->where('country_id', $countryId);
                })->with([
                    'childCategories.products.productImages',
                    'childCategories.products.productReviews',
                    'childCategories.products.variations',
                    'childCategories.products.units',
                    'childCategories.products.unit',
                    'childCategories.products.offers'
                ])->get();
        } else {
            // Retrieve all parent categories for visitors
            $categories = Category::whereNull('category_id')
                ->with([
                    'childCategories.products.productImages',
                    'childCategories.products.productReviews',
                    'childCategories.products.variations',
                    'childCategories.products.units',
                    'childCategories.products.unit',
                    'childCategories.products.offers'
                ])->get();
        }
    
        // Format the response
        $response = $categories->map(function ($category) use ($authenticatedUser) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'photo' => $category->photo,
                'color' => $category->color,
                'color_picker' => $category->color_picker,
                'childCategories' => $category->childCategories->map(function ($childCategory) use ($authenticatedUser) {
                    return [
                        'id' => $childCategory->id,
                        'name' => $childCategory->name,
                        'photo' => $childCategory->photo,
                        'color' => $childCategory->color,
                        'color_picker' => $childCategory->color_picker,
                        'products' => $this->formatProducts($childCategory->products, $authenticatedUser)
                    ];
                })
            ];
        });
    
        return response()->json(['data' => $response]);
    }

     public function getProducts($id, Request $request)
    {
        $token = $request->bearerToken();
        $authenticatedUser = null;
    
        if ($token) {
            $authenticatedUser = Auth::guard('user-api')->user();
        }
    
        // Load the category with its child categories and their products
        $category = Category::with([
            'childCategories.products.productImages', 
            'childCategories.products.productReviews', 
            'childCategories.products.variations', 
            'childCategories.products.units', 
            'childCategories.products.unit', 
            'childCategories.products.offers', 
            'products.productImages', 
            'products.productReviews', 
            'products.variations', 
            'products.units', 
            'products.unit', 
            'products.offers', 
            'countries'
        ])->find($id);
    
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
    
        // Prepare the response data
        $response = [
            'category' => [
                'name' => $category->name,
                'photo' => $category->photo,
                'color' => $category->color,
                'color_picker' => $category->color_picker,
            ],
            'childCategories' => [],
            'products' => $this->formatProducts($category->products, $authenticatedUser)
        ];
    
        foreach ($category->childCategories as $childCategory) {
            $childData = [
                'category' => [
                    'name' => $childCategory->name,
                    'photo' => $childCategory->photo,
                    'color' => $childCategory->color,
                    'color_picker' => $childCategory->color_picker,
                 
                ],
                'products' => $this->formatProducts($childCategory->products, $authenticatedUser)
            ];
            $response['childCategories'][] = $childData;
        }
    
        return response()->json(['data' => $response]);
    }

    private function formatProducts($products, $authenticatedUser)
{
    $formattedProducts = [];

    foreach ($products as $item) {
        if ($authenticatedUser) {
            $userType = $authenticatedUser->user_type;

            // Filter product data based on user_type
            if ($userType == 1) {
                $item->unit_name = $item->unit ? $item->unit->name_ar : null;
                $item->price = $item->selling_price_for_user;
                $item->quantity = $item->available_quantity_for_user;
            } elseif ($userType == 2) {
                $unit = $item->units->first();
                $item->unit_name = $unit ? $unit->name_ar : null;
                $item->price = $unit ? $unit->pivot->selling_price : null;
                $item->quantity = $item->available_quantity_for_wholeSale;
            }

            // Check if the product is a favorite for the authenticated user
            $item->is_favourite = $authenticatedUser->favourites()->where('product_id', $item->id)->exists();

            // Filter offers by user_type
            $item->has_offer = $item->offers()->where('user_type', $userType)->exists();
            $item->offer_id = $item->has_offer ? $item->offers()->where('user_type', $userType)->first()->id : 0;
            $item->offer_price = $item->has_offer ? $item->offers()->where('user_type', $userType)->first()->price : 0;

        } else {
            // Default data for non-authenticated users
            $item->unit_name = $item->unit ? $item->unit->name_ar : null;
            $item->price = $item->selling_price_for_user;
            $item->quantity = $item->available_quantity_for_user;
            $item->is_favourite = false;

            // Default offers for non-authenticated users (assume user_type = 1)
            $item->has_offer = $item->offers()->where('user_type', 1)->exists();
            $item->offer_id = $item->has_offer ? $item->offers()->where('user_type', 1)->first()->id : 0;
            $item->offer_price = $item->has_offer ? $item->offers()->where('user_type', 1)->first()->price : 0;
        }

        // Set additional product details
        $item->rating = $item->rating;
        $item->total_rating = $item->total_rating;
        $item->currency = $item->category->countries->first()->sympol ?? '';

        $formattedProducts[] = $item;
    }

    return $formattedProducts;
}



}

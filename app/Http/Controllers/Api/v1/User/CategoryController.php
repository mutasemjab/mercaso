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

        if ($authenticatedUser) {
            // Retrieve categories that are associated with the user's country
            $categories = Category::whereNull('category_id')
                ->with([
                    'childCategories.products' => function ($query) use ($productTypesToShow) {
                        $query->whereIn('product_type', $productTypesToShow);
                    },
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
                    'childCategories.products' => function ($query) use ($productTypesToShow) {
                        $query->whereIn('product_type', $productTypesToShow);
                    },
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
                'in_home_screen' => $category->in_home_screen,
                'childCategories' => $category->childCategories->map(function ($childCategory) use ($authenticatedUser) {
                    return [
                        'id' => $childCategory->id,
                        'name' => $childCategory->name,
                        'photo' => $childCategory->photo,
                        'in_home_screen' => $childCategory->in_home_screen,
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

        // Load the category with its child categories and their products (including brands)
        $category = Category::with([
            'childCategories.products' => function ($query) use ($productTypesToShow) {
                $query->whereIn('product_type', $productTypesToShow);
            },
            'childCategories.products.productImages',
            'childCategories.products.productReviews',
            'childCategories.products.variations',
            'childCategories.products.units',
            'childCategories.products.unit',
            'childCategories.products.offers',
            'childCategories.products.brand', // Add brand relationship
            'products' => function ($query) use ($productTypesToShow) {
                $query->whereIn('product_type', $productTypesToShow);
            },
            'products.productImages',
            'products.productReviews',
            'products.variations',
            'products.units',
            'products.unit',
            'products.offers',
            'products.brand', // Add brand relationship
        ])->find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Collect all products from main category and child categories
        $allProducts = collect($category->products);
        
        foreach ($category->childCategories as $childCategory) {
            $allProducts = $allProducts->merge($childCategory->products);
        }

        // Extract unique brands from all products
        $brands = $allProducts
            ->pluck('brand')
            ->filter() // Remove null values
            ->unique('id')
            ->map(function ($brand) {
                return [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'photo' => $brand->photo ?? null, // Include logo if available
                ];
            })
            ->values(); // Reset array keys

        // Prepare the response data
        $response = [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'photo' => $category->photo,
            ],
            'brands' => $brands, // Add brands list here
            'childCategories' => [],
            'products' => $this->formatProducts($category->products, $authenticatedUser)
        ];

        foreach ($category->childCategories as $childCategory) {
            $childData = [
                'category' => [
                    'id' => $childCategory->id,
                    'name' => $childCategory->name,
                    'photo' => $childCategory->photo,
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

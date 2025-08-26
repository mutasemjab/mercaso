<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->bearerToken();
        $authenticatedUser = null;
        $userType = 1; // Default user_type for non-authenticated users

        // If the user is authenticated, get their user_type
        if ($token) {
            $authenticatedUser = Auth::guard('user-api')->user();
            if ($authenticatedUser) {
                $userType = $authenticatedUser->user_type;
            }
        }

        // Determine which product types to show based on user type
        $productTypesToShow = [3]; // Always show "both" type products
        
        if ($userType == 1) {
            // Retail user: show retail (1) and both (3)
            $productTypesToShow[] = 1;
        } elseif ($userType == 2) {
            // Wholesale user: show wholesale (2) and both (3)
            $productTypesToShow[] = 2;
        }

        $itemlist = Product::with('category', 'variations', 'productImages', 'units', 'unit', 'offers')
            ->whereIn('product_type', $productTypesToShow);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $itemlist->where(function ($query) use ($search) {
                $query->where('name_ar', 'like', "%$search%")
                    ->orWhere('name_en', 'LIKE', "%$search%")
                    ->orWhere('number', 'LIKE', "%$search%")
                    ->orWhere('barcode', 'LIKE', "%$search%"); // Added barcode search
            });
        }

        // Get the results
        $itemlist = $itemlist->get();

        // Process each product with user-specific data
        foreach ($itemlist as $item) {
            if ($authenticatedUser) {
                // Set data based on the authenticated user's user_type
                if ($userType == 1) {
                    $item->unit_name = $item->unit ? $item->unit->name_ar : null;
                    $item->price = $item->selling_price_for_user;
                    $item->quantity = $item->available_quantity_for_user ?? 0;
                } elseif ($userType == 2) {
                    // For wholesale users, show wholesale pricing
                    if ($item->product_type == 2 || $item->product_type == 3) {
                        // Wholesale or both - show wholesale data
                        $unit = $item->units->first();
                        $item->unit_name = $unit ? $unit->name_ar : null;
                        $item->price = $unit ? $unit->pivot->selling_price : null;
                        $item->quantity = $item->available_quantity_for_wholeSale ?? 0;
                    } else {
                        // Fallback to retail data
                        $item->unit_name = $item->unit ? $item->unit->name_ar : null;
                        $item->price = $item->selling_price_for_user;
                        $item->quantity = $item->available_quantity_for_user ?? 0;
                    }
                }

                // Check if the product is a favorite
                $item->is_favourite = $authenticatedUser->favourites()->where('product_id', $item->id)->exists();

                // Filter offers based on user_type
                $item->has_offer = $item->offers()->where('user_type', $userType)->exists();
                $item->offer_id = $item->has_offer ? $item->offers()->where('user_type', $userType)->first()->id : 0;
                $item->offer_price = $item->has_offer ? $item->offers()->where('user_type', $userType)->first()->price : 0;
            } else {
                // Default values for non-authenticated users
                $item->unit_name = $item->unit ? $item->unit->name_ar : null;
                $item->price = $item->selling_price_for_user;
                $item->quantity = $item->available_quantity_for_user ?? 0;
                $item->is_favourite = false;

                // Default offers for non-authenticated users (user_type = 1)
                $item->has_offer = $item->offers()->where('user_type', 1)->exists();
                $item->offer_id = $item->has_offer ? $item->offers()->where('user_type', 1)->first()->id : 0;
                $item->offer_price = $item->has_offer ? $item->offers()->where('user_type', 1)->first()->price : 0;
            }

            // Set additional product details
            $item->rating = $item->rating;
            $item->total_rating = $item->total_rating;
        }

        // Apply sorting if requested
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_high_low':
                    $itemlist = $itemlist->sortByDesc(function ($item) {
                        return $item->has_offer ? $item->offer_price : $item->price;
                    })->values();
                    break;
                case 'price_low_high':
                    $itemlist = $itemlist->sortBy(function ($item) {
                        return $item->has_offer ? $item->offer_price : $item->price;
                    })->values();
                    break;
            }
        }

        return response()->json(['status' => 1, 'message' => trans('messages.success'), 'data' => $itemlist], 200);
    }

    public function productDetails(Request $request, $id)
    {
        $token = $request->bearerToken();
        $authenticatedUser = null;
        $userType = 1; // Default user_type for non-authenticated users

        if ($token) {
            $authenticatedUser = Auth::guard('user-api')->user();
            if ($authenticatedUser) {
                $userType = $authenticatedUser->user_type;
            }
        }

        // Determine which product types to show based on user type
        $productTypesToShow = [3]; // Always show "both" type products
        
        if ($userType == 1) {
            // Retail user: show retail (1) and both (3)
            $productTypesToShow[] = 1;
        } elseif ($userType == 2) {
            // Wholesale user: show wholesale (2) and both (3)
            $productTypesToShow[] = 2;
        }

        $item = Product::with('category', 'variations', 'productImages', 'units', 'unit', 'offers', 'category.countries')
            ->where('id', $id)
            ->whereIn('product_type', $productTypesToShow)
            ->first(); // Use first() instead of get() to fetch a single product

        if (!$item) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        if ($authenticatedUser) {
            if ($userType == 1) {
                $item->unit_name = $item->unit ? $item->unit->name_ar : null;
                $item->price = $item->selling_price_for_user;
                $item->quantity = $item->available_quantity_for_user ?? 0;
            } elseif ($userType == 2) {
                // For wholesale users, show wholesale pricing
                if ($item->product_type == 2 || $item->product_type == 3) {
                    // Wholesale or both - show wholesale data
                    $unit = $item->units->first();
                    $item->unit_name = $unit ? $unit->name_ar : null;
                    $item->price = $unit ? $unit->pivot->selling_price : null;
                    $item->quantity = $item->available_quantity_for_wholeSale ?? 0;
                } else {
                    // Fallback to retail data
                    $item->unit_name = $item->unit ? $item->unit->name_ar : null;
                    $item->price = $item->selling_price_for_user;
                    $item->quantity = $item->available_quantity_for_user ?? 0;
                }
            }

            $item->is_favourite = $authenticatedUser->favourites()->where('product_id', $item->id)->exists();
        } else {
            $item->unit_name = $item->unit ? $item->unit->name_ar : null;
            $item->price = $item->selling_price_for_user;
            $item->quantity = $item->available_quantity_for_user ?? 0;
            $item->is_favourite = false;
        }

        $item->rating = $item->rating;
        $item->total_rating = $item->total_rating;
        $item->currency = $item->category->countries->first()->sympol ?? '';
        
        // Filter offers by user_type
        $item->has_offer = $item->offers()->where('user_type', $userType)->exists();
        $item->offer_id = $item->has_offer ? $item->offers()->where('user_type', $userType)->first()->id : 0;
        $item->offer_price = $item->has_offer ? $item->offers()->where('user_type', $userType)->first()->price : 0;

        return response()->json(['data' => $item]);
    }

    public function latest(Request $request)
    {
        $token = $request->bearerToken();
        $authenticatedUser = null;
        $userType = 1; // Default user_type for non-authenticated users

        if ($token) {
            $authenticatedUser = Auth::guard('user-api')->user();
            if ($authenticatedUser) {
                $userType = $authenticatedUser->user_type;
            }
        }

        // Determine which product types to show based on user type
        $productTypesToShow = [3]; // Always show "both" type products
        
        if ($userType == 1) {
            // Retail user: show retail (1) and both (3)
            $productTypesToShow[] = 1;
        } elseif ($userType == 2) {
            // Wholesale user: show wholesale (2) and both (3)
            $productTypesToShow[] = 2;
        }

        $itemlist = Product::with('category', 'variations', 'productImages', 'units', 'unit')
            ->where('status', 1)
            ->whereIn('product_type', $productTypesToShow)
            ->get();

        foreach ($itemlist as $item) {
            if ($authenticatedUser) {
                if ($userType == 1) {
                    $item->unit_name = $item->unit ? $item->unit->name_ar : null;
                    $item->price = $item->selling_price_for_user;
                    $item->quantity = $item->available_quantity_for_user ?? 0;
                } elseif ($userType == 2) {
                    // For wholesale users, show wholesale pricing
                    if ($item->product_type == 2 || $item->product_type == 3) {
                        // Wholesale or both - show wholesale data
                        $unit = $item->units->first();
                        $item->unit_name = $unit ? $unit->name_ar : null;
                        $item->price = $unit ? $unit->pivot->selling_price : null;
                        $item->quantity = $item->available_quantity_for_wholeSale ?? 0;
                    } else {
                        // Fallback to retail data
                        $item->unit_name = $item->unit ? $item->unit->name_ar : null;
                        $item->price = $item->selling_price_for_user;
                        $item->quantity = $item->available_quantity_for_user ?? 0;
                    }
                }

                $item->is_favourite = $authenticatedUser->favourites()->where('product_id', $item->id)->exists();
            } else {
                $item->unit_name = $item->unit ? $item->unit->name_ar : null;
                $item->price = $item->selling_price_for_user;
                $item->quantity = $item->available_quantity_for_user ?? 0;
                $item->is_favourite = false;
            }
            
            $item->rating = $item->rating;
            $item->total_rating = $item->total_rating;
            
            // Filter offers by user_type
            $item->has_offer = $item->offers()->where('user_type', $userType)->exists();
            $item->offer_id = $item->has_offer ? $item->offers()->where('user_type', $userType)->first()->id : 0;
            $item->offer_price = $item->has_offer ? $item->offers()->where('user_type', $userType)->first()->price : 0;
        }

        return response()->json(['data' => $itemlist]);
    }

    public function offers(Request $request)
    {
        $token = $request->bearerToken();
        $authenticatedUser = null;
        $userType = 1; // Default user_type for non-authenticated users

        // If the user is authenticated, get their user_type
        if ($token) {
            $authenticatedUser = Auth::guard('user-api')->user();
            if ($authenticatedUser) {
                $userType = $authenticatedUser->user_type;
            }
        }

        // Determine which product types to show based on user type
        $productTypesToShow = [3]; // Always show "both" type products
        
        if ($userType == 1) {
            // Retail user: show retail (1) and both (3)
            $productTypesToShow[] = 1;
        } elseif ($userType == 2) {
            // Wholesale user: show wholesale (2) and both (3)
            $productTypesToShow[] = 2;
        }

        // Filter the products and offers based on the user_type and product_type
        $itemList = Product::with('category', 'variations', 'productImages', 'units', 'unit', 'category.countries')
            ->where('status', 1)
            ->whereIn('product_type', $productTypesToShow)
            ->whereHas('offers', function ($query) use ($userType) {
                $query->where('user_type', $userType); // Filter offers by user_type
            })
            ->orderBy('id', 'DESC')
            ->get();

        foreach ($itemList as $item) {
            if ($authenticatedUser) {
                // Set data based on the authenticated user's user_type
                if ($userType == 1) {
                    $item->unit_name = $item->unit ? $item->unit->name_ar : null;
                    $item->price = $item->selling_price_for_user;
                    $item->quantity = $item->available_quantity_for_user ?? 0;
                } elseif ($userType == 2) {
                    // For wholesale users, show wholesale pricing
                    if ($item->product_type == 2 || $item->product_type == 3) {
                        // Wholesale or both - show wholesale data
                        $unit = $item->units->first();
                        $item->unit_name = $unit ? $unit->name_ar : null;
                        $item->price = $unit ? $unit->pivot->selling_price : null;
                        $item->quantity = $item->available_quantity_for_wholeSale ?? 0;
                    } else {
                        // Fallback to retail data
                        $item->unit_name = $item->unit ? $item->unit->name_ar : null;
                        $item->price = $item->selling_price_for_user;
                        $item->quantity = $item->available_quantity_for_user ?? 0;
                    }
                }

                // Check if the product is a favorite
                $item->is_favourite = $authenticatedUser->favourites()->where('product_id', $item->id)->exists();
            } else {
                // Default values for non-authenticated users
                $item->unit_name = $item->unit ? $item->unit->name_ar : null;
                $item->price = $item->selling_price_for_user;
                $item->quantity = $item->available_quantity_for_user ?? 0;
                $item->is_favourite = false;
            }

            // Set other product-related details
            $item->rating = $item->rating;
            $item->total_rating = $item->total_rating;
            $item->currency = $item->category->countries->first()->sympol ?? '';
            
            // Filter offers by user_type
            $item->has_offer = $item->offers()->where('user_type', $userType)->exists();
            $item->offer_id = $item->has_offer ? $item->offers()->where('user_type', $userType)->first()->id : 0;
            $item->offer_price = $item->has_offer ? $item->offers()->where('user_type', $userType)->first()->price : 0;
        }

        return response()->json(['data' => $itemList]);
    }
}
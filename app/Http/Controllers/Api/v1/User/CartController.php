<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\UserCoupon;
use Illuminate\Http\Request;
use App\Http\Resources\CartResource;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{

      public function index()
    {
        $user = auth()->user();

        $carts = Cart::with([
            'product' => function ($query) {
                $query->with('category', 'variations', 'productImages', 'units', 'unit', 'offers');
            },
            'variation' // Include this if variation is directly related to the Cart model
        ])->where('user_id', auth()->id())->where('status', 1)->get();

        $token = request()->bearerToken();
        $authenticatedUser = null;

        if ($token) {
            $authenticatedUser = Auth::guard('user-api')->user();
        }

        $total = 0;
        $totalDiscount = 0;
        $totalTax = 0;
        $totalCrv = 0;

        foreach ($carts as $cart) {
            $item = $cart->product;

            if ($authenticatedUser) {
                $userType = $authenticatedUser->user_type;

                // Filter offers by user_type and get the correct offer for the authenticated user
                $item->has_offer = $item->offers()->where('user_type', $userType)->exists();
                $item->offer_price = $item->has_offer ? $item->offers()->where('user_type', $userType)->first()->price : null;

                // Set the price based on user_type and offers
                if ($userType == 1) {
                    $item->unit_name = $item->unit ? $item->unit->name_ar : null;
                    $item->price = $item->has_offer ? $item->offer_price : $item->selling_price_for_user;
                    $item->quantity = $item->available_quantity_for_user;
                } elseif ($userType == 2) {
                    $unit = $item->units->first();
                    $item->unit_name = $unit ? $unit->name_ar : null;
                    $item->price = $item->has_offer ? $item->offer_price : ($unit ? $unit->pivot->selling_price : null);
                    $item->quantity = $item->available_quantity_for_wholeSale;
                }

                $item->is_favourite = $authenticatedUser->favourites()->where('product_id', $item->id)->exists();
            }

            $item->rating = $item->rating;
            $item->total_rating = $item->total_rating;

            // Calculate the total price for the product in the cart
            $cart->total_price_product = round($cart->quantity * $item->price, 2);

            // Apply offer discount if available
            if ($item->has_offer) {
                $discount = round(($item->selling_price_for_user - $item->offer_price) * $cart->quantity, 2);
                $cart->total_price_product = round($item->offer_price * $cart->quantity, 2); // Set to offer price * quantity
                $totalDiscount += $discount;
            }

            // Calculate tax and CRV for this product (percentages)
            if ($item->tax && $item->tax > 0) {
                $productTax = round(($cart->total_price_product * $item->tax) / 100, 2);
                $totalTax += $productTax;
            }

            if ($item->crv && $item->crv > 0) {
                $productCrv = round(($cart->total_price_product * $item->crv) / 100, 2);
                $totalCrv += $productCrv;
            }

            // Add to the overall total
            $total += $cart->total_price_product;

        }

        // Apply coupon discount as a percentage of the total
        $couponDiscount = $this->applyCouponDiscount($user->id, $total);
        $totalDiscount += $couponDiscount;
        $totalAfterDiscounts = round($total - $couponDiscount, 2)+$totalTax+$totalCrv;

        // Update the cart records with the coupon discount
        foreach ($carts as $cart) {
            $cart->discount_coupon = round($couponDiscount, 2);
            $cart->save();
        }

        return response()->json([
            'data' => $carts,
            'total' => round($total, 2),
            'total_discount' => round($totalDiscount, 2),
            'total_after_discounts' => $totalAfterDiscounts,
            'total_tax' => round($totalTax, 2),
            'total_crv' => round($totalCrv, 2),
        ]);
    }


    private function applyCouponDiscount($userId, $total)
    {
        $couponDiscount = 0;

        // Fetch applied coupons
        $userCoupons = UserCoupon::where('user_id', $userId)->with('coupon')->get();

        foreach ($userCoupons as $userCoupon) {
            $coupon = $userCoupon->coupon;

            if ($coupon && $coupon->expired_at > now()) {
                // Calculate the discount as a percentage of the total
                $couponDiscount += $coupon->amount;
            }
        }

        return round($couponDiscount, 2);
    }



    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'nullable|integer',
        ]);

        // Fetch the authenticated user and their user_type
        $user = auth()->user();
        $userType = $user->user_type;

        // Fetch the product based on the provided product_id
        $product = Product::findOrFail($request->input('product_id'));

        // Filter offers by user_type and get the offer price if available for this user type
        $offer = $product->offers()->where('user_type', $userType)->first();

        // Determine the price based on user_type and available offers
        if ($offer) {
            // Use the offer price if there is a matching offer for the user_type
            $price = $offer->price;
        } else {
            // No matching offer, calculate the price based on user_type
            if ($userType == 1) {
                $price = $product->selling_price_for_user;
            } elseif ($userType == 2) {
                $unit = $product->units()->first();
                if ($unit) {
                    $price = $unit->pivot->selling_price;
                } else {
                    return response()->json(['message' => 'No unit found for the product'], 400);
                }
            }
        }

        // Check if the product is already in the cart for the authenticated user
        $cartItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $request->input('product_id'))
            ->where('variation_id', $request->input('variation_id'))
            ->where('status', 1)
            ->first();

        if ($cartItem) {
            // Update the quantity if the product is already in the cart
            $cartItem->quantity += $request->input('quantity');

            if ($cartItem->quantity < 1) {
                // Remove the item from the cart if quantity is zero
                $cartItem->delete();

                // Check if a coupon exists for the user and delete it if necessary
                $userCoupon = UserCoupon::where('user_id', $user->id)->first();
                if ($userCoupon) {
                    $userCoupon->delete();
                }

                return response()->json(['message' => 'Item removed from cart'], 200);
            } else {
                // Correctly calculate the total price for the product
                $cartItem->total_price_product = round($price * $cartItem->quantity, 2);
                $cartItem->price = round($price, 2);
                $cartItem->save();

                return response()->json($cartItem, 200);
            }
        } else {
            // Product is not in the cart, create a new entry with quantity set to 1
            $cart = new Cart();
            $cart->user_id = auth()->id();
            $cart->product_id = $request->input('product_id');
            $cart->variation_id = $request->input('variation_id');
            $cart->quantity = 1;
            $cart->price = round($price, 2);
            $cart->total_price_product = round($price, 2); // Set total price as price * quantity (1 in this case)
            $cart->status = 1;
            $cart->save();

            return response()->json($cart, 201);
        }
    }




    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'quantity' => 'required|integer',
        ]);

        // Find the cart item by ID
        $cart = Cart::where('user_id', auth()->id())->findOrFail($id);

        // Check if the product is already in the cart for the authenticated user
        $existingCartItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $cart->product_id)
            ->where('id', '!=', $id) // Exclude the current cart item
            ->first();

        if ($existingCartItem) {
            // Product is already in the cart, you might want to handle this case
            // Maybe merge the quantities, or show an error message
            return response()->json(['message' => 'Product already in the cart'], 400);
        }

        // Ensure that the quantity is at least one
        if ($cart->quantity <= 0) {
            // You can handle this case as per your requirements
            return response()->json(['message' => 'Quantity must be at least one'], 400);
        }

        // Update the quantity
        $cart->quantity += $request->input('quantity');
        $totalPrice = round($cart->product->selling_price * $cart->quantity, 2);
        $cart->total_price_product = $totalPrice;
        $cart->save();

        return response()->json($cart);
    }


    public function destroy($id)
    {
        // Find the cart item by ID and delete it
        $cart = Cart::where('user_id', auth()->id())->findOrFail($id);
        $cart->delete();

        return response()->json(null, 204);
    }

    public function destroyAll()
    {
        // Delete all cart items for the authenticated user
        Cart::where('user_id', auth()->id())->delete();

        return response()->json(null, 204);
    }
}

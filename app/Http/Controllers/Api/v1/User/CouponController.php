<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\UserCoupon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CouponController extends Controller
{

   public function applyCoupon(Request $request)
    {
        $user_id = auth()->user()->id;

        $this->validate($request, [
            'code' => 'required',
        ]);

        $couponCode = $request->input('code');

        DB::beginTransaction();
        try {
            // Check if the coupon code exists and is valid
            $coupon = Coupon::where('code', $couponCode)
                ->where('expired_at', '>', now())
                ->first();

            if (!$coupon) {
                throw ValidationException::withMessages(['code' => ['Invalid or expired coupon code']]);
            }

            // Check if the coupon has already been used by the user
            $alreadyUsed = UserCoupon::where('user_id', $user_id)
                ->where('coupon_id', $coupon->id)
                ->exists();

            if ($alreadyUsed) {
                throw ValidationException::withMessages(['code' => ['Coupon code has already been used']]);
            }

            // Apply the discount to the user's cart
            $carts = Cart::where('user_id', $user_id)->where('status', 1)->get();
            
            if ($carts->isEmpty()) {
                throw ValidationException::withMessages(['code' => ['No items in cart to apply coupon']]);
            }

            $totalCartValue = $carts->sum('total_price_product');
            $discountPercentage = $coupon->amount;
            $totalDiscountAmount = ($totalCartValue * $discountPercentage) / 100;

            // Distribute the discount proportionally among cart items
            foreach ($carts as $cart) {
                $itemProportion = $cart->total_price_product / $totalCartValue;
                $itemDiscount = $totalDiscountAmount * $itemProportion;
                $cart->discount_coupon = round($itemDiscount, 2);
                // Store which coupon was applied (you might need to add this column to cart table)
                $cart->applied_coupon_id = $coupon->id;
                $cart->save();
            }

            DB::commit();
            return response(['message' => 'Coupon applied successfully'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response(['error' => $e->getMessage()], 400);
        }
    }




}

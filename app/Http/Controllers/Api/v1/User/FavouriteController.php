<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\FavoriteResource;
use App\Http\Resources\ProductResource;
use App\Models\Favorite;
use App\Models\Favourite;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
  public function index()
    {
        $user = auth()->user();
        $favourites = $user->favourites()->with('category', 'variations', 'productImages', 'units', 'unit')->get();

        foreach ($favourites as $item) {
            $userType = $user->user_type;

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

            $item->is_favourite = $user->favourites()->where('product_id', $item->id)->exists();
            $item->rating = $item->rating;
            $item->total_rating = $item->total_rating;
            $item->has_offer = $item->offers()->exists();
            $item->offer_id = $item->has_offer ? $item->offers()->first()->id : 0;
            $item->offer_price = $item->has_offer ? $item->offers()->first()->price : 0;
        }

        return response()->json(['data' => $favourites]);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'product_id'=>'required|exists:products,id'
        ]);

        $favorite = Favourite::where('user_id',$request->user()->id)
            ->where('product_id',$request->product_id)->first();
        if($favorite){
            if ($favorite->delete()) {
                return response(['message' => 'Changed','is_favorite'=>false], 200);
            }else{
                return response(['errors' => ['Something wrong']], 403);
            }
        }
        $favorite = new Favourite();
        $favorite->user_id = $request->user()->id;
        $favorite->product_id = $request->product_id;
        if ($favorite->save()) {
            return response(['message' => 'Changed','is_favorite'=>true], 200);
        }else{
            return response(['errors' => ['Something wrong']], 403);
        }
    }

}

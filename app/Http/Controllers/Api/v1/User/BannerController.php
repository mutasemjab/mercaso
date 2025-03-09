<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SubCategoryResource;
use App\Models\Banner;
use App\Models\SubCategory;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class BannerController extends Controller
{

    public function index(Request $request)
    {
        $data = Banner::get();
        return response()->json(['data'=>$data]);

    }





}

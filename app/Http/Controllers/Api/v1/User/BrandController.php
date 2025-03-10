<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;


class BrandController extends Controller
{

    public function index()
    {
        $data = Brand::get();
        return response()->json(['data'=>$data]);

    }

    public function getBrandProduct($id)
    {
        $data = Product::where('brand_id',$id)->get();
        return response()->json(['data'=>$data]);

    }


}

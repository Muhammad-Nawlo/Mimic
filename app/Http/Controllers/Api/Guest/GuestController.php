<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Country;
use App\Http\Resources\CountryResource;
use App\Models\Category;

class GuestController extends Controller
{

    public function getAllCountries()
    {
        $countries=Country::All();
        if($countries->count() <=0)
        {
            return response()->json([
                'status'=>false,
                'message'=>__('site.no_records'),
            ],200);
        }

        return response()->json([
            'status'=>true,
            'countries'=>CountryResource::collection($countries),
        ]);
    }
    public function getAllCategories()
    {
        $categories=Category::all();
        if($categories->count() <=0)
        {
            return response()->json([
                'status'=>false,
                'message'=>__('site.no_records'),
            ],200);
        }

        return response()->json([
            'status'=>true,
            'categories'=>CategoryResource::collection($categories)->response()->getData(true),
        ],200);
    }

}

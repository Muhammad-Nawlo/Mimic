<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\HashtagResource;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Models\Hashtag;
use Illuminate\Support\Facades\Validator;
class HashtagController extends Controller
{
    public function SearchHastage(Request $request)
    {
        try {
            if (! $client = auth('client')->user()) {
                return response()->json([
                    'status'  => false,
                    'message' => __('site.user_not_found'),
                ], 200);
            }

        } catch (TokenExpiredException $e) {

            return response()->json([
                'status'  => false,
                'message' => __('site.token_expired'),
            ], 200);

        } catch (TokenInvalidException $e) {

            return response()->json([
                'status'  => false,
                'message' => __('site.token_invalid'),
            ], 200);

        } catch (JWTException $e) {

            return response()->json([
                'status'  => false,
                'message' => __('site.token_absent'),
            ], 200);
        }
        $validator=Validator::make($request->all(),[
            'title'   =>['nullable','string'],
            'ids'    =>'nullable | array',
            'ids'    =>'exists:hashtages,id',
            'category_id'=> 'nullable|exists:categories,id',

        ]);
        $hashtags=Hashtag::when($request->title,function($q)use($request){
            $q->where('title','like','%' .$request->title. '%');
        })->when($request->ids,function($q)use($request){
            $q->whereIn('id',$request->ids);
        })->when($request->category_id,function($q)use($request){
            $q->where('category_id',$request->category_id);
        })->get();

        return response()->json([
            'status'    =>true,
            'hashtags'  =>HashtagResource::collection($hashtags),
        ]);
    }
    public function addHastage(Request $request)
    {
        try {
            if (! $client = auth('client')->user()) {
                return response()->json([
                    'status'  => false,
                    'message' => __('site.user_not_found'),
                ], 200);
            }

        } catch (TokenExpiredException $e) {

            return response()->json([
                'status'  => false,
                'message' => __('site.token_expired'),
            ], 200);

        } catch (TokenInvalidException $e) {

            return response()->json([
                'status'  => false,
                'message' => __('site.token_invalid'),
            ], 200);

        } catch (JWTException $e) {

            return response()->json([
                'status'  => false,
                'message' => __('site.token_absent'),
            ], 200);
        }
        $validator=Validator::make($request->all(),[
            'title'   =>['required','string'],
            'category_id'=> 'required|exists:categories,id',
        ]);
        Hashtag::create([
            'title'=>$request->title,
        ]);

        return response()->json([
            'status'    =>true,
        ]);
    }

}

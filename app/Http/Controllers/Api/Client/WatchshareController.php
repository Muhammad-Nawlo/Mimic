<?php

namespace App\Http\Controllers\Api\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Models\Watch;
use App\Models\Shar;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class WatchshareController extends Controller
{
    public function watch(Request $request)
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
            'video_id'        =>'required | exists:videos,id',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=>false,
                'message'=>$validator->errors(),
            ]);
        }
        Watch::create([
            'video_id'  =>$request->video_id,
            'client_id' =>$client->id,
        ]);
        return response()->json([
            'status'=>true
        ]);

    }
    public function share(Request $request)
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
            'challenge_id'        =>'required | exists:challenges,id',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=>false,
                'message'=>$validator->errors(),
            ]);
        }
        Shar::create([
            'challenge_id'  =>$request->challenge_id,
            'client_id' =>$client->id,
        ]);
        return response()->json([
            'status'=>true
        ]);

    }
    public function watchStory(Request $request)
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
            'story_id'        =>'required | exists:stories,id',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=>false,
                'message'=>$validator->errors(),
            ]);
        }
        $watch=Watch::where('client_id',$client->id)->where('story_id',$request->story_id)->first();
        if(!empty($watch))
        {
            return response()->json([
                'status'=>false
            ]);
        }
        Watch::create([
            'story_id'  =>$request->story_id,
            'client_id' =>$client->id,
        ]);
        return response()->json([
            'status'=>true
        ]);

    }
}



<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChallengeResource;
use App\Models\Challenge;
use App\Models\Favourite;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Validator;

class FavouriteController extends Controller
{
    public function toggleFavourite(Request $request)
    {
        try {
            if (!$client = auth('client')->user()) {
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
        $validator = Validator::make($request->all(), [
            'challenge_id'  => 'required | exists:challenges,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
        //toggle like the video
        if ($request->challenge_id != null) {
            $fav = Favourite::where('client_id', $client->id)->where('challenge_id', $request->challenge_id)->first();
            if (!empty($fav)) {
                $fav->delete();
            } else {
                Favourite::create([
                    'challenge_id'  => $request->challenge_id,
                    'client_id'     => $client->id,
                ]);
            }
            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }


    public function getMyFavouriteChallenge()
    {
        try {
            if (!$client = auth('client')->user()) {
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

        $ids = Favourite::where('client_id', $client->id)->pluck('challenge_id');
        $challenges = Challenge::whereIn('status', ['accept', 'close'])->whereIn('id', $ids)->latest()->paginate(12);
        if ($challenges->count() <= 0) {
            return response()->json([
                'status'  => false,
                'message' => __('site.Not_found'),
            ], 200);
        }
        return response()->json([
            'status'    => true,
            'challenge'   => ChallengeResource::collection($challenges)->response()->getData(true),
        ]);
    }
}

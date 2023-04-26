<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\RankResource;
use App\Http\Resources\VideoResource;
use App\Models\Rank;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Models\Video;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

class RankController extends Controller
{


    public function getRanks()
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

        $ranks = Rank::all();
        if ($ranks->count() < 0) {
            return response()->json([
                'status'    => false,
                'message'   => __('site.Not_found'),
            ], 200);
        }

        return response()->json([
            'status'   => true,
            'rank_id'       => $client->rank_id ?? null,
            'challenge_num'      => $client->challenge_num ?? null,
            'like_num'      => $client->like_num ?? null,
            'video_num'      => $client->video_num ?? null,
            'invitation_num'      => $client->invite_num ?? null,
            'rank'       => RankResource::collection($ranks),
        ], 200);
    }
    protected function uploadImage($image, $path)
    {
        $imageName = $image->hashName();
        Image::make($image)->resize(360, 270)->save(public_path('uploads/' . $path . '/' . $imageName));
        return $imageName;
    }
}

<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoryResource;
use App\Http\Resources\VideoResource;
use App\Models\Challenge;
use App\Models\Story;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Models\Video;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

class StoryController extends Controller
{

    public function store(Request $request)
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
            ], 404);
        } catch (TokenInvalidException $e) {

            return response()->json([
                'status'  => false,
                'message' => __('site.token_invalid'),
            ], 404);
        } catch (JWTException $e) {

            return response()->json([
                'status'  => false,
                'message' => __('site.token_absent'),
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'videos'         => 'required|array',
            'videos.*'       => 'file|max:40000',
            'video_name'     => 'required|string| max:50 | min:1',
            'thumb'          => 'nullable |mimes:jpg,png,jpeg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
        $arr = array();
        if ($request->videos != null) {
            foreach ($request->videos as $video) {
                $filename = $video->getClientOriginalName();
                $video->move(public_path('/stories'), $filename);
                array_push($arr, $filename);
            }
        }
        //   $result=$this->CheckVideo(asset('stories/'.$request->video_name.'.m3u8'));
        //   if($result != null) {
        //       return response()->json([
        //         'status'        =>false,
        //         'reject_reason' =>$result,
        //       ]);
        //   }
        $story = Story::create([
            'video'          => $request->video_name . '.m3u8',
            'client_id'      => $client->id,
            'videos'         => json_encode($arr),
            'status'         => 'accept',
            'thumb'          => $request->thumb != null ? $this->uploadImage($request->thumb, 'stories') : null,
        ]);

        return response()->json([
            'status' => true,
            'story' => new StoryResource($story),
        ]);
    }

    public function getStories()
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

        $stories = Story::with(['reacts', 'client'])->whereDate('created_at', date('Y-m-d'))->where('client_id', '!=', $client->id)->withCount('watchs')->get(12);
        $MyStories = Story::with(['reacts', 'client'])->whereDate('created_at', date('Y-m-d'))->where('client_id', $client->id)->withCount('watchs')->get();


        return response()->json([
            'status'    => true,
            'story'       => StoryResource::collection($MyStories),
            'stories'        => StoryResource::collection($stories)->response()->getData(true),
        ], 200);
    }
    protected function uploadImage($image, $path)
    {
        $imageName = $image->hashName();
        Image::make($image)->resize(360, 270)->save(public_path($path . '/' . $imageName));
        return $imageName;
    }
}

<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Models\Challenge;
use App\Models\Reason;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Models\Video;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use App\Traits\NotificationTrait;
use App\Traits\RankTrait;

class VideoController extends Controller
{
    use NotificationTrait, RankTrait;
    public function index()
    {
        $videos = Video::query()->where('status', '=', 'accept');
        if (auth('client')->check()) {
            $videos->where('client_id', '!=', auth('client')->user()->id);
        }

        return response()->json([
            'status' => true,
            'videos' => VideoResource::collection($videos->latest()->paginate())
        ]);
    }
    public function store(Request $request)
    {
        try {
            if (!$client = auth('client')->user()) {
                return response()->json([
                    'status' => false,
                    'message' => __('site.user_not_found'),
                ], 404);
            }
        } catch (TokenExpiredException $e) {

            return response()->json([
                'status' => false,
                'message' => __('site.token_expired'),
            ], 404);
        } catch (TokenInvalidException $e) {

            return response()->json([
                'status' => false,
                'message' => __('site.token_invalid'),
            ], 404);
        } catch (JWTException $e) {

            return response()->json([
                'status' => false,
                'message' => __('site.token_absent'),
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max :50',
            'video' => 'file|max:40000',
            'thumb' => 'nullable |mimes:jpg,png,jpeg,gif',
            'interestings' => 'nullable|array',
            'interestings.*' => [
                Rule::requiredIf(function () use ($request) {
                    return $request->interestings !== null;
                }), Rule::exists('interestings', 'id')
            ],
            'mentions' => 'nullable|array',
            'mentions.*' => [Rule::requiredIf(function () use ($request) {
                return $request->mentions !== null;
            }), Rule::exists('clients', 'id')]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
        $filename = $request->video->getClientOriginalName();
        $request->video->move(public_path('/videos'), $filename);

        $video = Video::create([
            'title' => $request->title,
            'video' =>  $filename,
            'challenge_id' => $request->challenge_id,
            'client_id' => $client->id,
            'status' => 'accept',
            'thumb' => $request->thumb != null ? $this->uploadImage($request->thumb, 'videos') : null,
        ]);

        //save interesting for a video
        $video->interestings()->sync($request->interestings ?? []);

        //save mentions
        $video->clients()->sync($request->mentions ?? []);

        $this->VideoNum($client);
        return response()->json([
            'status' => true,
            'video' => new VideoResource($video->load(['clients', 'interestings'])),
        ]);
    }

    public function getMyVideos(Request $request)
    {
        try {
            if (!$client = auth('client')->user()) {
                return response()->json([
                    'status' => false,
                    'message' => __('site.user_not_found'),
                ], 200);
            }
        } catch (TokenExpiredException $e) {

            return response()->json([
                'status' => false,
                'message' => __('site.token_expired'),
            ], 200);
        } catch (TokenInvalidException $e) {

            return response()->json([
                'status' => false,
                'message' => __('site.token_invalid'),
            ], 200);
        } catch (JWTException $e) {

            return response()->json([
                'status' => false,
                'message' => __('site.token_absent'),
            ], 200);
        }
        $validator = Validator::make($request->all(), [
            'status' => ['nullable', Rule::in('pending', 'accept', 'reject')],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
        $videos = Video::with(['client'])->when($request->status, function ($q) use ($request) {
            $q->where('status', $request->status);
        })->where('client_id', $client->id)->paginate(12);
        if ($videos->count() < 0) {
            return response()->json([
                'status' => false,
                'message' => __('site.Not_found'),
            ], 200);
        }

        return response()->json([
            'status' => true,
            'video' => VideoResource::collection($videos)->response()->getData(true),
        ], 200);
    }

    protected function uploadImage($image, $path)
    {
        $imageName = $image->hashName();
        Image::make($image)->resize(360, 270)->save(public_path($path . '/' . $imageName));
        return $imageName;
    }

    public function AddReason(Request $request)
    {
        try {
            if (!$client = auth('client')->user()) {
                return response()->json([
                    'status' => false,
                    'message' => __('site.user_not_found'),
                ], 404);
            }
        } catch (TokenExpiredException $e) {

            return response()->json([
                'status' => false,
                'message' => __('site.token_expired'),
            ], 404);
        } catch (TokenInvalidException $e) {

            return response()->json([
                'status' => false,
                'message' => __('site.token_invalid'),
            ], 404);
        } catch (JWTException $e) {

            return response()->json([
                'status' => false,
                'message' => __('site.token_absent'),
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'required | string |max :50',
            'video_id' => 'required|exists:videos,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
        $video = Video::where('client_id', $client->id)->find($request->video_id);
        if (empty($video)) {
            return response()->json([
                'status' => false,
            ]);
        }
        Reason::create([
            'reason' => $request->reason,
            'video_id' => $request->video_id,
            'type' => 'client',
        ]);

        return response()->json([
            'status' => true,
        ]);
    }
}

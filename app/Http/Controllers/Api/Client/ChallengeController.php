<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\AcceptChallengeResource;
use App\Http\Resources\ChallengeResource;
use App\Http\Resources\VideoResource;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Models\Challenge;
use App\Models\Hashtag;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Traits\AiModelTrait;
use App\Traits\RankTrait;
use App\Traits\NotificationTrait;
use App\Models\Video;
use Intervention\Image\ImageManagerStatic as Image;

class ChallengeController extends Controller
{
    use AiModelTrait, RankTrait, NotificationTrait;
    public function CreateChallenge(Request $request)
    {
        try {
            if (!$client = auth('client')->user()) {
                return response()->json([
                    'status'  => false,
                    'message' => __('site.user_not_found'),
                ], 404);
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
            'title'          => 'required|string| max:50 | min:1',
            'description'     => 'nullable|string|max:1000',
            'category_id'    => 'required|numeric|exists:categories,id',
            'end_date'       => 'required|date|date_format:Y-m-d|after_or_equal:tody',
            'videos'         => 'required|array',
            'videos.*'       => 'file|max:40000',
            'video_name'     => 'required|string| max:50 | min:1',
            'hashtags'       => 'nullable | array',
            'hashtags.*'     => 'exists:hashtags,id',
            'requests'       => 'nullable | array',
            'requests.*'     => 'exists:clients,id',
            'hashtagNames'   => 'nullable | array',
            'hashtagNames.*' => 'string|min:1|max:100',
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
                $video->move(public_path('/videos'), $filename);
                array_push($arr, $filename);
            }
        }
        //   $result=$this->CheckVideo(asset('videos/'.$request->video_name.'.m3u8'));
        //   if($result != null) {
        //       return response()->json([
        //         'status'        =>false,
        //         'reject_reason' =>$result,
        //       ]);
        //   }

        // $this->dispatch(new VideoProcssingJob($filename,$filename1));


        $arrOfHashtages = $this->handleHashtages($request->hashtagNames, $request->hashtags, $request->category_id);

        $challenge = Challenge::create([
            'title'          => $request->title,
            'category_id'    => $request->category_id,
            'description'    => $request->description,
            'creater_id'     => $client->id,
            'end_date'       => $request->end_date,
            'status'         => 'accept',
            'requests'       => $request->requests != null ? json_encode($request->requests) : null,
            'hashtags'       => $arrOfHashtages != null ? json_encode($arrOfHashtages) : null,
        ]);
        $video = Video::create([
            'title'         => $request->title,
            'video'         => $request->video_name . '.m3u8',
            'client_id'     => $client->id,
            'challenge_id'  => $challenge->id,
            'category_id'   => $request->category_id,
            'videos'        => json_encode($arr),
            'status'        => 'accept',
            'thumb'         => $request->thumb != null ? $this->uploadImage($request->thumb, 'videos') : null,
        ]);
        $this->ChallengeNum($client);
        $this->sendRequestNotify($challenge, $video, $request->requests);

        return response()->json([
            'status'    => true,
            'accept_challenge' => new AcceptChallengeResource($challenge),
        ]);
    }


    public function getChallengesByCreaterId(Request $request)
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
            'creater_id'    => 'required|exists:clients,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
        $creater_id = $request->creater_id;
        $challengs = Challenge::with(['category', 'client'])->whereNotIn('status', ['pending', 'reject'])->where('creater_id', $creater_id)->paginate(12);
        if ($challengs->count() <= 0) {
            return response()->json([
                'status'  => false,
                'message' => __('site.Not_found'),
            ], 200);
        }
        return response()->json([
            'status'    => true,
            'challenge' => ChallengeResource::collection($challengs)->response()->getData(true),
        ]);
    }

    public function getCurrentChallenges()
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

        if ($client->cateories_ids != null) {
            $challengs = Challenge::with(['category', 'client'])->whereDate('end_date', '>=', date('Y-m-d h:m:s'))->where('status', 'accept')->whereIn('category_id', json_decode($client->cateories_ids))->paginate(12);
        } else {
            $challengs = Challenge::with(['category', 'client'])->whereDate('end_date', '>=', date('Y-m-d h:m:s'))->where('status', 'accept')->paginate(12);
        }
        if ($challengs->count() <= 0) {
            return response()->json([
                'status'  => false,
                'message' => __('site.Not_found'),
            ], 200);
        }
        return response()->json([
            'status'    => true,
            'accept_challenge' => AcceptChallengeResource::collection($challengs)->response()->getData(true),
        ]);
    }

    public function getChallengeById(Request $request)
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
            'challenge_id'    => 'required|exists:challenges,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        $challenge = Challenge::with(['category', 'client'])->where('status', 'accept')->where('id', $request->challenge_id)->first();

        if (empty($challenge)) {
            return response()->json([
                'status'  => false,
                'message' => __('site.Not_found'),
            ], 200);
        }
        return response()->json([
            'status'    => true,
            'accept_challenge' => new AcceptChallengeResource($challenge),
        ]);
    }

    public function getCurrentChallengesVideos(Request $request)
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
            'challenge_id'    => 'required|exists:challenges,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
        $videos = Video::with(['client'])->where('status', 'accept')->where('challenge_id', $request->challenge_id)->paginate(12);
        if ($videos->count() <= 0) {
            return response()->json([
                'status'  => false,
                'message' => __('site.Not_found'),
            ], 200);
        }
        return response()->json([
            'status'    => true,
            'video' => VideoResource::collection($videos)->response()->getData(true),
        ]);
    }

    public function searchInChallenges(Request $request)
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
            'category_id'   => 'nullable|array',
            'category_id.*' => 'exists:categories,id',
            'period'        => ['nullable', Rule::in(0, 1, 2)],
            'client_id'     => 'nullable|exists:clients,id',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        $categories = json_decode($client->cateories_ids);
        if ($request->category_id != null) {
            $categories = $request->category_id;
        }
        if ($request->period != null) {
            if ($request->period == '0') {
                $request->period = date('Y-m-d', strtotime("this week"));
            }
            if ($request->period == '1') {
                $request->period = date('Y-m-01');
            }
            if ($request->period == '2') {
                $request->period = date('Y-01-01');
            }
        }
        $challengs = Challenge::with(['category', 'client'])->whereIn('status', ['accept', 'close'])
            ->when($request->period, function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->period);
            })->when($request->client_id, function ($q) use ($request) {
                $q->where('creater_id', $request->client_id);
            })->whereIn('category_id', $categories)
            ->latest()
            ->paginate(12);

        if ($challengs->count() <= 0) {
            return response()->json([
                'status'  => false,
                'message' => __('site.Not_found'),
            ], 200);
        }
        return response()->json([
            'status'    => true,
            'accept_challenge' => AcceptChallengeResource::collection($challengs)->response()->getData(true),
        ], 200);
    }

    public function getMyChallenges(Request $request)
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
            'category_id'   => ['nullable', 'exists:categories,id'],
            'status'        => ['nullable', Rule::in('pending', 'accept', 'reject', 'close')],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
        $challengs = Challenge::with(['category', 'client'])->where('creater_id', $client->id)->when($request->category_id, function ($q) use ($request) {
            $q->where('category_id', $request->category_id);
        })->when($request->status, function ($q) use ($request) {
            $q->where('status', $request->status);
        })->paginate(12);
        if ($challengs->count() <= 0) {
            return response()->json([
                'status'  => false,
                'message' => __('site.Not_found'),
            ], 200);
        }
        return response()->json([
            'status'    => true,
            'challenge' => ChallengeResource::collection($challengs)->response()->getData(true),
        ]);
    }

    public function SearchByHashtagIDInCurrentChallenges(Request $request)
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
            'id'             => 'required | exists:hashtags,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
        if ($client->cateories_ids != null) {
            $challengs = Challenge::with(['category', 'client'])->whereDate('end_date', '>=', date('Y-m-d h:m:s'))->whereJsonContains('hashtags', $request->id)->where('status', 'accept')->whereIn('category_id', json_decode($client->cateories_ids))->paginate(12);
        } else {
            $challengs = Challenge::with(['category', 'client'])->whereDate('end_date', '>=', date('Y-m-d h:m:s'))->whereJsonContains('hashtags', $request->id)->where('status', 'accept')->paginate(12);
        }
        if ($challengs->count() <= 0) {
            return response()->json([
                'status'  => false,
                'message' => __('site.Not_found'),
            ], 200);
        }
        return response()->json([
            'status'    => true,
            'accept_challenge' => AcceptChallengeResource::collection($challengs)->response()->getData(true),
        ]);
    }

    protected function uploadImage($image, $path)
    {
        $imageName = $image->hashName();
        Image::make($image)->resize(360, 270)->save(public_path($path . '/' . $imageName));
        return $imageName;
    }

    public function handleHashtages($hashNames, $hashIDs, $catid)
    {
        $arr = array();
        if ($hashNames != null) {
            foreach ($hashNames as $hashName) {
                $hash = Hashtag::create([
                    'title'         => $hashName,
                    'category_id'   => $catid,
                ]);
                array_push($arr, $hash->id);
            }
        }
        if ($hashIDs != null) {
            $arr = array_merge($arr, array_map('intval', $hashIDs));
        }
        return $arr;
    }

    public function joinChallenges(Request $request)
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
            'client_id'   => ['nullable', 'exists:categories,id'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
        $chaIds = Video::where('client_id', $request->client_id)->pluck('challenge_id') ?? [];
        if (count($chaIds) < 0) {
            return response()->json([
                'status'  => false,
                'message' => __('site.Not_found'),
            ], 200);
        }

        $challengs = Challenge::with(['category', 'client'])->where('creater_id', '!=', $request->client_id)->whereIn('id', $chaIds)->paginate(12);
        if ($challengs->count() <= 0) {
            return response()->json([
                'status'  => false,
                'message' => __('site.Not_found'),
            ], 200);
        }
        return response()->json([
            'status'    => true,
            'challenge' => ChallengeResource::collection($challengs)->response()->getData(true),
        ]);
    }
}

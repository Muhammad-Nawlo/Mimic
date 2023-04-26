<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReplayResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Validator;
use App\Models\Replay;
use App\Traits\NotificationTrait;

class ReplayController extends Controller
{
    use NotificationTrait;

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
            'comment_id'        => 'required|exists:comments,id',
            'mentions'            => 'nullable|array',
            'mentions.*'        => 'exists:clients,id',
            'body'              => 'required|string|min:1|max:600',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 200);
        }
        $mentions = null;
        if ($request->mentions != null) {
            $mentions = json_encode($request->mentions);
        }
        $replay = Replay::create([
            'body'          => $request->body,
            'client_id'     => $client->id,
            'comment_id'    => $request->comment_id,
            'mentions'      => $mentions,
        ]);
        // Start Notification
        $comment = Comment::find($request->comment_id);
        if ($comment->client_id != $client->id) {
            $this->sendNotification('Mimic', 'Mimic', 'رد على تعليقك ' . $client->user_name, $client->user_name . ' Replied to Your Comment', $client->id, $comment->client_id, null, $comment->video_id, $comment->id, $replay->id, 'reply');
        }
        //End Notification
        return response()->json([
            'status'    => true,
            'replay' => new ReplayResource($replay),
        ], 200);
    }

    public function update(Request $request)
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
            'replay_id'         => 'required|exists:replays,id',
            'mentions'            => 'nullable|array',
            'mentions.*'        => 'exists:clients,id',
            'body'              => 'required|string|min:1|max:600',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 200);
        }
        $replay = Replay::where('client_id', $client->id)->find($request->replay_id);
        if (empty($replay)) {
            return response()->json([
                'status'    => false,
                'message'   => __('site.Not_found'),
            ], 200);
        }
        $mentions = $replay->mentions;
        if ($request->mentions != null) {
            $mentions = json_encode($request->mentions);
        }
        $replay->update([
            'body'          => $request->body,
            'mentions'      => $mentions,
        ]);
        return response()->json([
            'status'    => true,
            'replay' => new ReplayResource($replay),
        ], 200);
    }

    public function delete(Request $request)
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
            'replay_id'         => 'required|exists:replays,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 200);
        }
        $replay = Replay::where('client_id', $client->id)->find($request->replay_id);
        if (empty($replay)) {
            return response()->json([
                'status'    => false,
                'message'   => __('site.Not_found'),
            ], 200);
        }
        $replay->likes()->delete();
        $replay->delete();
        return response()->json([
            'status'    => true,
        ], 200);
    }

    public function getReplysByCommentId(Request $request)
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
            'comment_id'        => 'required|exists:comments,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 200);
        }
        $rid = Replay::where('comment_id', $request->comment_id)->latest()->first()->id;
        $replays = Replay::where('comment_id', $request->comment_id)->where('id', '!=', $rid)->paginate(12);
        if ($replays->count() < 0) {
            return response()->json([
                'status'    => false,
                'message'   => __('site.Not_found'),
            ], 200);
        }

        return response()->json([
            'status'    => true,
            'replay'  => ReplayResource::collection($replays)->response()->getData(true),
        ], 200);
    }
}

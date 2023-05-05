<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Challenge;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Validator;
use App\Models\Comment;
use App\Models\Video;
use App\Traits\NotificationTrait;

class CommentController extends Controller
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
            'challenge_id'          => 'required|exists:challenges,id',
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
        $comment = Comment::create([
            'body'          => $request->body,
            'client_id'     => $client->id,
            'challenge_id'      => $request->challenge_id,
            'mentions'      => $mentions,
        ]);

        // Start Notification
        $challenge = Challenge::find($request->challenge_id);
        if ($challenge->client_id != $client->id) {
            $this->sendNotification('Mimic', 'Mimic', ' علق على الفديو الخاص بك' . $client->user_name, $client->user_name . ' Comment Your Video', $client->id, $challenge->creater_id, $challenge->id, $challenge->videos()->first()->id, $comment->id, null, 'comment');
        }
        //End Notification
        return response()->json([
            'status'    => true,
            'comment' => new CommentResource($comment),
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
        $comment = Comment::where('client_id', $client->id)->find($request->comment_id);
        if (empty($comment)) {
            return response()->json([
                'status'    => false,
                'message'   => __('site.Not_found'),
            ], 200);
        }
        $mentions = null;
        if ($request->mentions != null) {
            $mentions = json_encode($request->mentions);
        }
        $comment->update([
            'body'          => $request->body,
            'mentions'      => $mentions,
        ]);
        return response()->json([
            'status'    => true,
            'comment' => new CommentResource($comment),
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
            'comment_id'        => 'required|exists:comments,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 200);
        }
        $comment = Comment::where('client_id', $client->id)->find($request->comment_id);
        if (empty($comment)) {
            return response()->json([
                'status'    => false,
                'message'   => __('site.Not_found'),
            ], 200);
        }
        $comment->replies()->delete();
        $comment->likes()->delete();
        $comment->delete();
        return response()->json([
            'status'    => true,
        ], 200);
    }
    public function getCommentsByChallengeId(Request $request)
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
            'challenge_id'          => 'required|exists:challenges,id',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
        $comments = Comment::where('challenge_id', $request->challenge_id)->paginate(12);

        if ($comments->count() < 0) {
            return response()->json([
                'status'    => false,
                'message'   => __('site.Not_found'),
            ], 200);
        }

        return response()->json([
            'status'    => true,
            'comment'  => CommentResource::collection($comments)->response()->getData(true),
        ], 200);
    }
}

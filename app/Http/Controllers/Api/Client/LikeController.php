<?php

namespace App\Http\Controllers\Api\Client;
use App\Http\Controllers\Controller;
use App\Http\Resources\StoryCustResource;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Replay;
use App\Models\Story;
use App\Models\Video;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Validator;
use App\Traits\NotificationTrait;
use App\Traits\RankTrait;

class LikeController extends Controller
{
    use NotificationTrait,RankTrait;

    public function toggleLike(Request $request)
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
            'video_id'        =>'nullable | exists:videos,id',
            'comment_id'      =>'nullable | exists:comments,id',
            'replay_id'       =>'nullable | exists:replays,id',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=>false,
                'message'=>$validator->errors(),
            ]);
        }
        //toggle like the video
        if($request->video_id != null)
        {
            $video=Video::with('client')->find($request->video_id);
            $like=Like::where('client_id',$client->id)->where('video_id',$request->video_id)->first();
            if(!empty($like))
            {
                $video->client->update(['like_num'=>(($video->client->like_num ?? 0)-1)]);
                $like->delete();
            }else{
                Like::create([
                    'video_id'  =>$request->video_id,
                    'client_id' =>$client->id,
                ]);
                // Start Notification
                    if($video->client_id!=$client->id)
                    {
                        $video->client->update(['like_num'=>(($video->client->like_num ?? 0)+1)]);
                        // $this->sendNotification('مميك','Mimic','تفاعل مع الفديو الخاص بك '.$client->user_name, $client->user_name .' Like Your Video',$client->id,$video->client_id,null,null,$video->id,null,'like');
                    }
                //End Notification
            }
            return response()->json([
                'status'=>true
            ]);
        }
        //toggle like the comment
        if($request->comment_id != null)
        {
            $comment=Comment::with('client')->find($request->comment_id);
            $like=Like::where('client_id',$client->id)->where('comment_id',$request->comment_id)->first();
            if(!empty($like))
            {
                $comment->client->update(['like_num'=>(($comment->client->like_num ?? 0)-1)]);
                $like->delete();
            }else{
                Like::create([
                    'comment_id'  =>$request->comment_id,
                    'client_id'   =>$client->id,
                ]);
                // Start Notification
                    if($comment->client_id!=$client->id)
                    {
                        $comment->client->update(['like_num'=>(($comment->client->like_num ?? 0)+1)]);
                        // $this->sendNotification('مميك','Mimic','تفاعل مع تعليقك '.$client->user_name, $client->user_name .' Like Your Comment',$client->id,$comment->client_id,$comment->id,null,null,null,'like');
                    }
                //End Notification
            }

            return response()->json([
                'status'=>true
            ]);
        }
        //toggle like the replay
        if($request->replay_id != null)
        {
            $replay=Replay::with('client')->find($request->replay_id);
            $like=Like::where('client_id',$client->id)->where('replay_id',$request->replay_id)->first();
            if(!empty($like))
            {
                $replay->client->update(['like_num'=>(($replay->client->like_num ?? 0)-1)]);
                $like->delete();
            }else{
                Like::create([
                    'replay_id'  =>$request->replay_id,
                    'client_id'   =>$client->id,
                ]);
                // Start Notification
                    if($replay->client_id!=$client->id)
                    {
                        $replay->client->update(['like_num'=>(($replay->client->like_num ?? 0)+1)]);
                        // $this->sendNotification('مميك','Mimic','تفاعل مع ردك '.$client->user_name, $client->user_name .' Like Your Replay',$client->id,$replay->client_id,null,$replay->id,null,null,'like');
                    }
                //End Notification
            }
            return response()->json([
                'status'=>true
            ]);
        }
        return response()->json([
            'status'=>false
        ]);

    }

    public function reactStory(Request $request)
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
        $like=Like::where('story_id',$request->story_id)->where('client_id',$client->id)->first();
        if(empty($like)){
            Like::create([
                'story_id'  =>$request->story_id,
                'client_id' =>$client->id,
                'react_num' =>1,
            ]);
        }else{
            $like->update(['react_num'=>($like->react_num+1)]);
        }
        return response()->json([
            'status'=>true
        ]);

    }

    public function getReacts(Request $request)
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
        $stories=Like::where('story_id',$request->story_id)->get();
        return response()->json([
            'status'    =>true,
            'stories'   =>StoryCustResource::collection($stories),
        ]);

    }
}



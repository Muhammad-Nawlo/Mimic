<?php
namespace App\Traits;

use App\Models\Client;
use App\Models\Notification;
use App\Http\Resources\ClientCustomResource;
use Illuminate\Support\Facades\Http;
trait NotificationTrait{
    protected function sendNotification($title_ar,$title_en,$body_ar,$body_en,$sender,$reciver,$challenge_id,$video_id,$comment_id,$replay_id,$type)
    {
      $notify=Notification::create([
        'ar'            =>['title'=>$title_ar,'body'=>$body_ar],
        'en'            =>['title'=>$title_en,'body'=>$body_en],
        'sender_id'     =>$sender,
        'reciver_id'    =>$reciver,
        'comment_id'    =>$comment_id,
        'replay_id'     =>$replay_id,
        'video_id'      =>$video_id,
        'challenge_id'  =>$challenge_id,
        'type'          =>$type,
      ]);


      $reciver=Client::find($reciver);
      $device_token=$reciver->device_token;
     if($device_token != null)
      {
          $SERVER_API_KEY ='';
          $apiURL    = 'https://fcm.googleapis.com/fcm/send';
          $postInput = [
                        "to" =>$device_token,
                        "notification"     =>[
                                "title"             => $title_en,
                                "body"              => $body_en,
                                "mutable_content"   =>true,
                                "soun"              =>"default"
                            ],
                        "andriod"=>[
                            "priority"    =>"HIGH",
                            "notification"=>[
                                "notification_priority"    =>"PRIORITY_MAX",
                                "sound"                    =>"default",
                                "default_sound"            =>true,
                                "default_vibrate_timings"  =>true,
                                "default_light_settings"   =>true
                            ]
                        ],
                        "data"=>[
                                'id'            =>$notify->id,
                                'title'         =>$title_en,
                                'body'          =>$body_en,
                                'sender'        =>new ClientCustomResource($this->client) ?? null,
                                'comment_id'    => $comment_id ,
                                'replay_id'     => $replay_id ,
                                'video_id'      => $video_id ,
                                'challenge_id'  => $challenge_id ,
                                'type'          => $type ,
                                'read'          => $this->read_at != null ? true : false ,
                                'date'          =>date('Y-m-d h:i:s' , strtotime($this->created_at)),
                        ]
                       ];
          $headers   = [
                        'Authorization'=>'key='.$SERVER_API_KEY,
                       ];
          $response = Http::withHeaders($headers)->post($apiURL, $postInput);
      }

    }

    public function sendRequestNotify($challenge,$video,$requests)
    {
        if($requests != null)
        {
            foreach($requests as $id){
                if($video->client_id !=$id ){
                    $this->sendNotification('Mimic','Mimic','هل تستطيع ان تتحدانى؟','Can You Challenge Me?',$video->client_id,$id,$challenge->id,$video->id,null,null,'request');
                }
            }
        }
    }
}

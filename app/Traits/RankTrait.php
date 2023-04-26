<?php
namespace App\Traits;
trait RankTrait{
   public function ChallengeNum($client)
   {
        $client->update(['challenge_num'=>(($client->challenge_num ?? 0)+1)]);
   }
   public function VideoNum($client)
   {
        $client->update(['video_num'=>(($client->video_num ?? 0)+1)]);
   }
}

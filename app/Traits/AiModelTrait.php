<?php
namespace App\Traits;
trait AiModelTrait{
    public function CheckVideo($url)
    {
          $reject_reason=null;
          $params = array(
            'stream_url'    =>  $url,
            'workflow'      => 'wfl_bvQVgZhKfeHu2t6pqdVvW', //workflow_id
            'api_user'      => '140293082',                 //api_user
            'api_secret'    => 'ygNBeVLwQSjmVToR4SK5',      //api_secret
          );
          $ch = curl_init('https://api.sightengine.com/1.0/video/check-workflow-sync.json?'.http_build_query($params));
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $response = curl_exec($ch);
          curl_close($ch);
          $output = json_decode($response, true);
          if($output['summary']['action']=='reject') {
                $reject_reason = $output['summary']['reject_reason'];
          }
          return $reject_reason;
    }
}

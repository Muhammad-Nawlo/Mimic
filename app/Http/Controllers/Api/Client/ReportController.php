<?php

namespace App\Http\Controllers\Api\Client;
use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    
    public function store(Request $request)
    {
        try {
            if (! $client = auth('client')->user()) {
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

        $validator=Validator::make($request->all(),[
            'text'          =>'required | string |max :5000',
            'video_id'      =>'required | exists:videos,id',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=>false,
                'message'=>$validator->errors(),
            ]);
        }

        $video=Report::create([
            'text'          =>$request->text,
            'video_id'      =>$request->video_id,
            'client_id'     =>$client->id,
            'status'        =>'new',
        ]);
      
        return response()->json([
            'status'=>true,
        ]);

    }

   
}



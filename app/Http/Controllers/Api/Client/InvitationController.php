<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Validator;

class InvitationController extends Controller
{
    public function store(Request $request)
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
            'sender_id'        =>'required|exists:clients,id',
            'mac_address'	   =>'required|string',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=>false,
                'message'=>$validator->errors(),
            ],200);
        }
        if(!empty(Invitation::where('mac_address',$request->mac_address)->first()) || !empty(Client::where('mac_address',$request->mac_address)->first()))
        {
            return response()->json([
                'status'    =>false,
            ],200);
        }
        $inv=Invitation::create([
            'sender_id'     =>$request->sender_id,
            'reciver_id'    =>$client->id,
            'mac_address'   =>$request->mac_address,
        ]);
        $inv->sender->update(['invite_num'=>(($inv->sender->invite_num ?? 0)+1)]);
        return response()->json([
            'status'    =>true,
        ],200);
    }
}

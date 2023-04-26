<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;

use App\Http\Resources\ClientResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class authenticationController extends Controller
{
    public function authenticate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:clients,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors(),
            ], 200);
        }
        $credentials = ['email' => $request->email, 'password' => $request->password];
        try {
            if (!$token = auth('client')->attempt($credentials)) {
                return response()->json([
                    'status'  => false,
                    'message' => __('site.passwored or email is wrong'),
                ], 200);
            }
        } catch (JWTException $e) {
            return response()->json([
                'status'  => false,
                'message' => __('site.some thing is wrong'),
            ], 200);
        }

        $client = auth('client')->user();
        if ($client->verified_email == false) {
            return response()->json([
                'status'  => false,
                'type'    => 0,     //verify email
                'message' => __('site.You Need To Verify Your Account'),
            ], 200);
        }
        if ($client->status == 'Blocked') {
            return response()->json([
                'status'  => false,
                'type'    => 3,     //verify email
                'message' => __('site.You Account is Blocked'),
            ], 200);
        }

        if ($request->has('device_token')) {
            $client->device_token   = $request->device_token;
            $client->save();
        }
        if ($client->cateories_ids == null) {
            return response()->json([
                'status'  => true,
                'type'    => 1,     //choose the favourite categories
                'client'      => new ClientResource($client),
                'token'      => $token,
            ], 200);
        }
        return response()->json([
            'status'    => true,
            'type'      => 2,
            'client'        => new ClientResource($client),
            'token'        => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        $client = Auth::guard('client')->user();
        $client->update(['device_token' => null]);
        Auth::guard('client')->logout('true');
        return response()->json([
            'status'  => true,
            'message' => __('site.Logout Successfully'),
        ], 200);
    }
}

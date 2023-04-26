<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Http\Controllers\Controller;
use App\Notifications\SendVerifySMS;
use Illuminate\Auth\Events\Registered;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\ClientVerifyEmail;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;


class registerController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|min:2|max:100|unique:clients,user_name',
            'email' => 'required|email|max:255|unique:clients,email',
            'password' => 'required|confirmed|min:8|max:255',
            'password_confirmation' => "required|string|same:password",
            'device_token' => 'nullable',
            'old_email' => 'nullable|email',
            'phone' => 'nullable|string|min:6|max:20',
            'mobile_number' => 'required|string|max:255|unique:clients,mobile_number',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                'message' => $validator->errors(),
            ], 200);
        }
        if ($request->old_email != null) {
            Client::where('email', $request->old_email)->delete();
        }
        $client = Client::create([
            'user_name' => str_replace(" ", "", $request->user_name),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'code' => $this->createCode(new Client()),
            'status' => 'UnBlocked',
            'phone' => $request->get('phone'),
            'mobile_number' => $request->get('mobile_number'),
            'rank_id' => 1,
            "verified_email" => true
        ]);


        if ($request->has('device_token')) {
            $client->device_token = $request->device_token;
        }
        if ($request->has('mac_address')) {
            $client->mac_address = $request->mac_address;
        }
        $client->save();

        return response()->json([
            "status" => true,
            'user_name' => $client->user_name,
            'client' => new ClientResource($client),
            'token' => JWTAuth::fromUser($client)

        ], 200);
    }

    public function createCode($model)
    {
        $code = Str::random(8);
        if ($model->where('code', $code)->get()->count() > 0) {
            $this->createCode($model);
        }
        return $code;
    }

    public function authSocial(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'social_id' => 'required|string',
            'user_name' => 'nullable|string|min:2|max:100',
            'phone' => 'nullable|string|min:6|max:20',
            'device_token' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                'message' => $validator->errors(),
            ], 200);
        }
        $client = Client::where('social_id', $request->social_id)->first();
        if (!empty($client)) {
            $token = JWTAuth::fromUser($client);
            if ($client->categories_ids == null) {
                return response()->json([
                    'status' => true,
                    'type' => 'login',
                    'category' => 0,   //choose the favourite categories
                    'client' => new ClientResource($client),
                    'token' => $token,
                ], 200);
            }
            return response()->json([
                "status" => true,
                'type' => 'login',
                'category' => 1,
                'client' => new ClientResource($client),
                'token' => $token,
            ], 200);
        }
        if (empty($client) && ($request->user_name == null)) {
            return response()->json([
                "status" => false,
                'type' => 'register',
                'message' => __('site.send register data'),
            ], 200);
        }

        $client = Client::create([
            'user_name' => $request->get('user_name'),
            'social_id' => $request->get('social_id'),
            'code' => $this->createCode(new Client()),
            'status' => 'UnBlocked',
            'phone' => $request->get('phone'),
            'rank_id' => 1,
        ]);

        if ($request->has('device_token')) {
            $client->device_token = $request->device_token;
        }

        if ($request->has('mac_address')) {
            $client->mac_address = $request->mac_address;
        }

        $client->save();
        $token = JWTAuth::fromUser($client);
        return response()->json([
            "status" => true,
            'type' => 'login',
            'category' => 0, //choose the favourite categories
            'client' => new ClientResource($client),
            'token' => $token,
        ], 200);
    }

    public function resetMobileVerificationCode(Request $request)
    {
        auth('client')->user()->sendMobileVerificationNotification(true);
        return response()->json([
            "status" => true,
            "message" => __('site.Mobile Number Code Check'),
        ]);
    }
}

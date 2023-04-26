<?php

namespace App\Http\Controllers\Api\client;

use App\Http\Controllers\Controller;
use App\Http\Resources\notificationResource;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        try {
            if (!$client = auth('client')->user()) {
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

        $notifications = Notification::with(['sender', 'comment', 'replay', 'video', 'challenge'])->where('reciver_id', $client->id)->latest()->paginate(10);
        if ($notifications->count() <= 0) {
            return response()->json([
                'status' => false,
                'message' => __('site.no_records'),
            ]);
        }

        return response()->json([
            'status'  => true,
            'notifications' => notificationResource::collection($notifications)->response()->getData(true),
        ], 200);
    }
    public function getunReadNotificationsCount()
    {
        try {
            if (!$client = auth('client')->user()) {
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

        $countUnreadNotify = Notification::where('reciver_id', $client->id)->where('read_at', null)->count() ?? 0;

        return response()->json([
            'status'  => true,
            'count_unread_notification' => $countUnreadNotify,
        ], 200);
    }
    public function deleteNotify(Request $request)
    {
        try {
            if (!$client = auth('client')->user()) {
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
        $validator = Validator::make($request->all(), [
            'notify_id' => 'required | exists:notifications,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'messages' => $validator->errors(),
            ]);
        }
        $notification = Notification::where('reciver_id', $client->id)->find($request->notify_id);
        if (empty($notification)) {
            return response()->json([
                'status' => false,
                'message' => __('site.no_records'),
            ]);
        }
        $notification->delete();
        return response()->json([
            'status'  => true,
        ], 200);
    }

    public function changeNotifyStatus(Request $request)
    {
        try {
            if (!$client = auth('client')->user()) {
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
        $validator = Validator::make($request->all(), [
            'notify_id' => 'required | exists:notifications,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'messages' => $validator->errors(),
            ]);
        }
        $notification = Notification::where('reciver_id', $client->id)->where('status', null)->where('type', 'request')->find($request->notify_id);
        if (empty($notification)) {
            return response()->json([
                'status' => false,
                'message' => __('site.no_records'),
            ]);
        }
        $notification->update(['status' => 1]);
        return response()->json([
            'status'  => true,
        ], 200);
    }

    public function readNotification()
    {
        try {
            if (!$client = auth('client')->user()) {
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
        $notifications = Notification::where('reciver_id', $client->id)->where('read_at', null)->get();
        if ($notifications->count() > 0) {
            foreach ($notifications as $notify) {
                $notify->update(['read_at' => date('d/m/y h:m:i')]);
            }
            return response()->json(['status' => true]);
        }
        return response()->json(['status' => true]);
    }
}

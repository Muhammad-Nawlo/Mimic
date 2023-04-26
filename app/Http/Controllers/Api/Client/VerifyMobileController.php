<?php

namespace App\Http\Controllers\Api\Client;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VerifyMobileController extends Controller
{
    public function __invoke(Request $request)
    {
        //Redirect user to dashboard if mobile already verified
        if ($request->user()->hasVerifiedMobile()) return response()->json([
            'status' => true,
            'message' => __('site.Mobile Number Verified'),
        ], 200);

        $request->validate([
            'code' => ['required', 'numeric'],
        ]);

        // Code correct
        if ($request->code === auth('client')->user()->mobile_verify_code) {
            // check if code is still valid
            $secondsOfValidation = (int)config('mobile.seconds_of_validation');
            if ($secondsOfValidation > 0 && auth('client')->user()->mobile_verify_code_sent_at->diffInSeconds() > $secondsOfValidation) {
                auth('client')->user()->sendMobileVerificationNotification(true);
                return response()->json([
                    'status' => false,
                    'message' => __('site.Mobile Number Code Expired')
                ]);
            } else {
                auth('client')->user()->markMobileAsVerified();
                return response()->json([
                    'status' => true,
                    'message' => __('site.Mobile Verify'),
                ]);
            }
        }

        // Max attempts feature
        if (config('mobile.max_attempts') > 0) {
            if (auth('client')->user()->mobile_attempts_left <= 1) {
                if (auth('client')->user()->mobile_attempts_left == 1) auth('client')->user()->decrement('mobile_attempts_left');

                //check how many seconds left to get unbanned after no more attempts left
                $seconds_left = (int)config('mobile.attempts_ban_seconds') - auth('client')->user()->mobile_last_attempt_date->diffInSeconds();
                if ($seconds_left > 0) {
                    return response()->json([
                        'status' => true,
                        'message' => __('site.Mobile Number Code Waiting Error', ['seconds' => $seconds_left]),
                    ]);
                }

                //Send new code and set new attempts when user is no longer banned
                auth('client')->user()->sendMobileVerificationNotification(true);
                return response()->json([
                    'status' => false,
                    'message' => __('site.Mobile Number Code New Code')
                ]);
            }

            auth('client')->user()->decrement('mobile_attempts_left');
            auth('client')->user()->update(['mobile_last_attempt_date' => now()]);
            return response()->json([
                'status' => false,
                'message' => __('site.Mobile Number Code Attempt Error', ['attempts' => $request->user()->mobile_attempts_left])
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => __('site.Mobile Number Code Error')
        ]);
    }
}

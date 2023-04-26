<?php

namespace App\Http\Controllers\Api\Client;
use App\Mail\ForgetPassword;
use App\Models\Client;
use App\Models\ClientForgetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class resetPasswordController
{
     public function clientForgetPassword(Request $request)
     {
         $validator=Validator::make($request->all(),[
             'email' =>'required|email|exists:clients,email',
         ]);
         if($validator->fails())
         {
             return response()->json([
                 'status'=>false,
                 'message'=>$validator->errors(),
             ],200);
         }
         $client=Client::where('email', $request->email)->where('verified_email',true)->first();
         if(empty($client))
         {
            return response()->json([
                'status'=>false,
                'message'=>__('site.Please Verify Your Account First'),
            ],200);
         }
         $token=$this->createToken(new ClientForgetPassword());

         $verifies=ClientForgetPassword::where('email',$request->email)->delete();

         $verify=ClientForgetPassword::create([
             'email' =>$request->email,
             'token' =>$token,
         ]);

         if(!empty($client))
         {
             Mail::to($request->email)->send(new ForgetPassword($client->user_name,$token));
             return response()->json([
                 'status'=>true,
                 'message'=>__('site.Code Send To Your Email Please Check Your Mail To Reset Your Password')
             ],200);
         }
     }
     public function clientResetPassword(Request $request)
     {
         $validator=Validator::make($request->all(),[
             'token'                =>'required|exists:client_forget_passwords,token',
             'password'             =>'required | min:8 |max : 20|string| confirmed',
             'password_confirmation'=>"required|same:password",
         ]);
         if($validator->fails())
         {
             return response()->json([
                 'status'=>false,
                 'message'=>$validator->errors(),
             ],200);
         }
         $tokenRow=ClientForgetPassword::where('token', $request->token)->first();
         if(empty($tokenRow)){
             return response()->json([
                 'status'=>false,
                 'message'=>__('site.Please Write The Right Code'),
             ],200);
         }
         $client=Client::where('email', $tokenRow->email)->first();
         if(empty($client)){
             return response()->json([
                 'status'=>false,
                 'message'=>__('site.Please Write The Right Code'),
             ],200);
         }
         $client->update([
             'password'=>Hash::make($request->password),
         ]);
         $tokenRow->delete();

             return response()->json([
                 'status'=>true,
                 'message'=>__('site.Your Password Reset Successfully'),
             ],200);
     }
     public function createToken($model)
    {
        $token=Str::random(4);
        if($model->where('token',$token)->get()->count()>0)
        {
            $this->createToken($model);
        }
        return $token;
    }
}

<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class profileController
{
    public function getProfile()
    {
        try {
            if (!$client = auth('client')->user()) {
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
        $sum = 0;
        $contributed = 0;
        foreach ($client->videos as $video) {
            $sum += $video->like_num;
            if ($video->challenge_id != null) {
                $contributed++;
            }
        }
        return response()->json([
            'status'  => true,
            'video_count' => $client->videos->count() ?? 0,
            'sum' => $sum,
            'contributors' => $contributed,
            'client' => new ClientResource($client),
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        try {
            if (!$client = auth('client')->user()) {
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

        $validator = Validator::make($request->all(), [
            'user_name'            => 'required|string|max:255',
            'image'                => 'nullable|image',
            'country_id'           => 'nullable|exists:countries,id',
            'city_id'              => 'nullable|exists:cities,id',
            'date_of_birth'        => 'nullable|date ',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                'errors' => $validator->errors(),
            ], 402);
        }

        if ($request->has('user_name')) {
            $client->user_name  = $request->get('user_name');
        }

        if ($request->has('country_id')) {
            $client->country_id  = $request->get('country_id');
        }
        if ($request->has('city_id')) {
            $client->city_id  = $request->get('city_id');
        }
        if ($request->has('date_of_birth')) {
            $client->date_of_birth  = $request->get('date_of_birth');
        }

        if ($request->hasFile('image')) {
            if ($client->image != null) {
                Storage::disk('public_uploads')->delete('/clients/' . $client->image);
            }
            $client->image = $this->uploadImage($request->file('image'), 'clients');
        }

        if ($client->save()) {
            return response()->json([
                'status'  => true,
                'client' => new ClientResource($client),
            ], 200);
        } else {
            return response()->json([
                'status'  => false,
                'message' => __('site.profile updated failed'),
            ], 200);
        }
    }

    public function addYouFavouriteCategory(Request $request)
    {
        try {
            if (!$client = auth('client')->user()) {
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

        $validator = Validator::make($request->all(), [
            'cateories_ids'        => "required|array",
            'cateories_ids.*'      => 'exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                'message' => $validator->errors(),
            ], 200);
        }

        if ($request->has('cateories_ids')) {
            $client->cateories_ids  = json_encode($request->get('cateories_ids'));
        }
        if ($client->save()) {
            return response()->json([
                'status'  => true,
                'client' => new ClientResource($client),
            ], 200);
        } else {
            return response()->json([
                'status'  => false,
                'message' => __('site.Category updated failed'),
            ], 200);
        }
    }

    public function changeClientPassword(Request $request)
    {
        // return $request;
        try {
            if (!$client = auth('client')->user()) {
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
        $validator = Validator::make($request->all(), [
            'oldPassword'             => 'required|string|min:8',
            'password'                => 'required|string|min:8',
            'password_confirmation'   => 'required|string|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"    => false,
                'message'   => $validator->errors(),
            ], 200);
        }
        if (Hash::check($request->oldPassword, $client->password)) {
            $client->password  = Hash::make($request->get('password'));
            $client->save();
            return response()->json([
                'status'  => true,
                'message' => __('site.password change Successfully'),
            ], 200);
        } else {
            return response()->json([
                'status'  => false,
                'message' => __('site.old password is wrong'),
            ], 200);
        }
    }

    protected function uploadImage($image, $path)
    {
        $imageName = $image->hashName();
        Image::make($image)->resize(360, 270)->save(public_path('uploads/' . $path . '/' . $imageName));
        return $imageName;
    }

    public function searchClients(Request $request)
    {
        try {
            if (!$client = auth('client')->user()) {
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

        $validator = Validator::make($request->all(), [
            'user_name'            => 'nullable|string|max:255',
            'code'                 => 'nullable|exists:clients,code',
            'ids'                  => 'nullable|array',
            'ids.*'                  => 'exists:clients,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                'message' => $validator->errors(),
            ], 200);
        }
        $clients = Client::when($request->user_name, function ($q) use ($request) {
            $q->where('user_name', 'like', '%' . $request->user_name . '%');
        })->when($request->ids, function ($q) use ($request) {
            $q->whereIn('id', $request->ids);
        })->when($request->code, function ($q) use ($request) {
            $q->where('code', $request->code);
        })->get();

        if ($clients->count() < 0) {
            return response()->json([
                "status" => false,
                'message' => __('site.no_records'),
            ], 200);
        }

        return response()->json([
            'status'  => true,
            'client' => ClientResource::collection($clients),
        ], 200);
    }

    public function myStatistics()
    {
        try {
            if (!$client = auth('client')->user()) {
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
        $sum = 0;
        $contributed = 0;
        foreach ($client->videos as $video) {
            $sum += $video->like_num;
            if ($video->challenge_id != null) {
                $contributed++;
            }
        }

        return response()->json([
            'status'  => true,
            'video_count' => $client->videos->count() ?? 0,
            'sum' => $sum,
            'contributors' => $contributed,
        ], 200);
    }
    public function ClientById(Request $request)
    {
        try {
            if (!$client = auth('client')->user()) {
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

        $validator = Validator::make($request->all(), [
            'id'            => 'requiredif:user_name,==,null|exists:clients,id',
            'user_name'     => 'requiredif:id,==,null|exists:clients,user_name',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                'message' => $validator->errors(),
            ], 200);
        }
        if ($request->user_name != null) {
            $client = Client::where('user_name', $request->user_name)->first();
        }
        if ($request->id != null) {
            $client = Client::where('id', $request->id)->first();
        }
        $sum = 0;
        $contributed = 0;
        foreach ($client->videos as $video) {
            $sum += $video->like_num;
            if ($video->challenge_id != null) {
                $contributed++;
            }
        }

        if (empty($client)) {
            return response()->json([
                "status" => false,
                'message' => __('site.no_records'),
            ], 200);
        }

        return response()->json([
            'status'  => true,
            'video_count' => $client->videos->count() ?? 0,
            'sum' => $sum,
            'contributors' => $contributed,
            'client' => new ClientResource($client),
        ], 200);
    }
}

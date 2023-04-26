<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\InterestingResource;
use App\Models\Interesting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InterestingController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'interestings' => InterestingResource::collection(Interesting::all())
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'interesting' => ['required', 'array'],
            'interesting.*' => ['required', Rule::exists('interestings', 'id')]
        ]);
        auth('client')->user()->interestings()->sync($request->interesting);
        return response()->json(['status' => true]);
    }
}

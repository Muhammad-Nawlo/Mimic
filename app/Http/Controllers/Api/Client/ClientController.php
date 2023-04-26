<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();
        if ($request->search) {
            $query->where('user_name', 'Like', '%' . $request->search . '%');
        }
        return response()->json([
            'status' => true,
            'data' => ClientResource::collection($query->get())
        ]);
    }
}

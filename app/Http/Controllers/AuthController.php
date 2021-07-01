<?php

namespace App\Http\Controllers;

use App\Services\ClientService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        //1. Make login in supabase
        $supabaseApiUrl = env('SUPABASE_API_CLIENT_URL') .'/singIn';

        $response = Http::post($supabaseApiUrl,$request->all());

        $userInfo  = json_decode($response->getBody()->getContents());
        
        if(!$userInfo->success){
            return response()->json($userInfo, 403);
        }
        //2. get client info by supabase user id


        $client = (new ClientService())->getClientByEmail($request->email);

        return response()->json([
            'success' => true,
            'userInfo' => $userInfo,
            'clientInfo' => $client
        ]);
    }

    public function signOut(Request $request)
    {
        $supabaseApiUrl = env('SUPABASE_API_CLIENT_URL') .'/signOut';

        $response = Http::post($supabaseApiUrl,$request->all());

        $response  = json_decode($response->getBody()->getContents());

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }
}

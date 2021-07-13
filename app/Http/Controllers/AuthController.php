<?php

namespace App\Http\Controllers;

use App\Services\ClientService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        //1. Make login in supabase
        $supabaseApiUrl = env('SUPABASE_API_CLIENT_URL') .'/singIn';

        $response = Http::post($supabaseApiUrl,$request->all());

        $userInfo  = json_decode($response->getBody()->getContents());
        
        if(!$userInfo->success){
            return response()->json([
                'success' => false,
                'message' => $userInfo->data->error->message,
                'data' => null
            ], 401);
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

    public function register(Request $request)
    {
        dd($request->all());
         //1. validate if user exist in users table
         $request->validate([
            'user.email' => 'required|unique:mysql2.users,email',
            'user.password' => 'required'
        ],[
            'user.email.required' => 'El email es obligatorio',
            'user.email.unique' => 'Este correo ya se encuentra registrado',
            'user.password.required' => 'La contraseña es obligatoria'
        ]);

        //2. Create a user in Facturascript
        $response = $this->clientService->create($request);

        //3. Create user here
        $user = User::create([
            'name' => $request->nombre,
            'email' => $request->user['email'],
            'password' => bcrypt($request->user['password'])
        ]);

        $credentials = [
            'email' => $request->user['email'],
            'password' => $request->user['password']
        ];
       
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $client = (new ClientService())->getClientByEmail($credentials['email']);
        return $this->respondWithTokenAndClient($token, $client);
    }
}

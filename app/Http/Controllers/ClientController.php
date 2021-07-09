<?php

namespace App\Http\Controllers;

use App\Services\ClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class ClientController extends Controller
{
    private $clientService;

    public function __construct()
    {
        $this->clientService = new ClientService();
    }

    public function all()
    {
        $response = $this->clientService->all();

        return response()->json([
            'success' => $response->success,
            'message' => null,
            'data' => $response->data
        ]);
    }
    
    public function store(Request $request)
    {
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
        return $this->respondWithTokenAndUser($token, $client);
    }

    public function get($codclient): JsonResponse
    {
        $response = $this->clientService->getClient($codclient);

        return response()->json(['success' => $response->success, 'message' => null, 'data' => $response->data]);
    }

    protected function respondWithTokenAndUser($token, $client)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'clientInfo' => $client
        ]);
    }
}

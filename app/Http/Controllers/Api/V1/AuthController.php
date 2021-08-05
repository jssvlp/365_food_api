<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ClientService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Actions\UserForgotPassword;

class AuthController extends Controller
{

    private $clientService;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','signup','loginFacturasCript','forgotPassword']]);
        $this->clientService = new ClientService();
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $client = $this->clientService->getClientByEmail($credentials['email']);
        return $this->respondWithTokenAndClient($token, $client);
    }

    public function loginFacturasCript()
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized', 'data' => null], 200);
        }
        $client = $this->clientService->getClientByEmail($credentials['email']);
        return $this->respondWithTokenAndClient($token, $client);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['success' => true, 'message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'newPassword' => 'required|min:8'
        ]);
       
        $user = User::find(request()->user()->id);
        $user->password = bcrypt($request->newPassword);

        $user->save();

        return response()->json(['success' => true, 'message' => 'Contraseña modificada correctamente!']);
    }

    public function forgotPassword(Request $request, UserForgotPassword $action)
    {
        $request->validate([
            'email' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        $action->handle($user);

         return response()->json(['success' => true, 'message' => 'Correo de recuperación enviado correctamente']);

    }

    public function signup(Request $request)
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
        return $this->respondWithTokenAndClient($token, $client);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


    protected function respondWithTokenAndClient($token, $client)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'clientInfo' => $client
        ]);
    }
}

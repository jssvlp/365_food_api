<?php

namespace App\Http\Controllers;

use App\Services\ClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
    public function store(Request $request): JsonResponse
    {

        //1. Create user in supabase
        $supabaseApiUrl = env('SUPABASE_API_CLIENT_URL') .'/signUp';
       
        $response = Http::post($supabaseApiUrl,$request->user);
        $userSupabase  = json_decode($response->getBody()->getContents());
        
        if(!$userSupabase->success)
        {
            return response()->json([
                'success' => false,
                'message' => $userSupabase->data->error->message,
                'data' => null
            ], 401);

        }
        //2. Create a user in Facturascript
        $response = $this->clientService->create($request);
        
        if(!$response->success)
        {
            $supabaseApiUrl = env('SUPABASE_API_CLIENT_URL'). '/user';

            $deleteCreatedUserInSupabase = Http::delete($supabaseApiUrl, [
                'uid' =>  $userSupabase->data->user->id
            ]);
            //$decoded = json_decode($deleteCreatedUserInSupabase->getBody()->getContents());

            return response()->json([
                'success' => $response->success,
                'message' => $response->message,
                'data' => $response->data
             ], 400);
        }

        if(!$response->success){
            return response()->json([
               'success' => $response->success,
               'message' => $response->message,
               'data' => $response->data
            ]);
        }

        
        $responseDecoded  = json_decode($userSupabase->getBody()->getContents());

        //3. Update the client info with de supabase info

        return response()->json([
            'success' => $response->success,
            'message' => $response->message,
            'data' => $response->data]);
    }

    public function get($codclient): JsonResponse
    {
        $response = $this->clientService->getClient($codclient);

        return response()->json(['success' => $response->success, 'message' => null, 'data' => $response->data]);
    }
}

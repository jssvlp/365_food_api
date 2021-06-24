<?php

namespace App\Http\Controllers;

use App\Services\ClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    private $clientService;

    public function __construct()
    {
        $this->clientService = new ClientService();
    }

    public function store(Request $request): JsonResponse
    {
        $response = $this->clientService->create($request);

        return response()->json(['success' => $response->success, 'message' => $response->message, 'data' => $response->data]);
    }

    public function get($codclient): JsonResponse
    {
        $response = $this->clientService->getClient($codclient);

        return response()->json(['success' => $response->success, 'message' => null, 'data' => $response->data]);
    }
}

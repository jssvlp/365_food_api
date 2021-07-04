<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\AddressService;

class AddressController
{
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $address = (new AddressService())->create($request);

        return response()->json([
            'success' => $address->success,
            'message' => $address->message,
            'address' => $address->data
        ]);
    }

    public function get($codcliente): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Consulta realizada correctamente!',
            'data' => (new AddressService())->byClient($codcliente)
        ]);
    }
}

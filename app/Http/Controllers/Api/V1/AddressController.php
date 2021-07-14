<?php


namespace App\Http\Controllers\Api\V1;


use Illuminate\Http\Request;
use App\Services\AddressService;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $address = (new AddressService())->create($request);

        return response()->json([
            'success' => $address->success,
            'message' => $address->message,
            'data' => $address->data['data']
        ]);
    }


    public function get($codcliente): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Consulta realizada correctamente!',
            'addresses' => (new AddressService())->byClient($codcliente)
        ]);
    }
}

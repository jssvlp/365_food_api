<?php

namespace App\Http\Controllers;

use App\Models\Tracking;
use App\Services\ClientService;
use App\Services\FacturascriptService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $order = (new OrderService())->createOrder($request);
       
        $items = collect($request->items);

        $items = $items->map( function ($item){
            return [
                'name' => $item['descripcion'],
                'quantity' => $item['cantidad']
            ];
        });
        
        $client = (new ClientService())->getClient($order->data['codcliente']);

        $trackingBody = [
            "orderNumber"=> $order->data['codigo'],
            "status"=> "Pendiente",
            "clientCode" => $order->data['codcliente'],
            "clientName"=> $client->data['nombre'],
            "phone"=> $client->data['telefono1'],
            "address"=> $request->direccionorden,
            "orderDetail"=> $items
        ];

        Tracking::create($trackingBody);

        return response()->json([
            'success' => $order->success,
            'message' => $order->message,
            'data' => $order->data
        ]);
    }

}

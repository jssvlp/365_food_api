<?php

namespace App\Http\Controllers;

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

        $supabaseApiUrl = env('SUPABASE_API_CLIENT_URL') .'/orders';

        $items = collect($request->items);

        $items = $items->map( function ($item){
            return [
                'name' => $item['descripcion'],
                'quantity' => $item['cantidad']
            ];
        });
      
        $client = (new ClientService())->getClient($order->data['codcliente']);

        $orderBody = [
            "orderNumber"=> $order->data['codigo'],
            "status"=> "Pendiente",
            "client"=> $client->data['nombre'],
            "phone"=> $client->data['telefono1'],
            "address"=> $request->direccionorden,
            "orderDetail"=> $items
        ];


        $supabaseApiResponse = Http::post($supabaseApiUrl,$orderBody);
        $decoded = json_decode($supabaseApiResponse->getBody()->getContents());

        //Create tracking in Supabase

        return response()->json([
            'success' => $order->success,
            'message' => $order->message,
            'data' => $order->data
        ]);
    }

}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\OrderCreated;
use App\Services\ClientService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\OrderTrackingUpdated;
use App\Events\OrderTrackingUpdatedForKitchen;
use App\Models\Order;

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

        $orderBody = [
            "orderNumber"=> $order->data['codigo'],
            "status"=> "Pendiente",
            "clientCode" => $order->data['codcliente'],
            "clientName"=> $client->data['nombre'],
            "phone"=> $client->data['telefono1'],
            "address"=> $request->direccionorden,
            "orderDetail"=> $items
        ];

        $_order = Order::create($orderBody);

        broadcast(new OrderCreated($_order));

        return response()->json([
            'success' => $order->success,
            'message' => $order->message,
            'data' => [
                'facturascript' => $order->data,
                'realtime' => _$order
            
        ]);
    }

    public function pending()
    {
        $orders = Order::where('delivered', false)->get();
        return response()->json([
            'success' => true,
            'orders' => $orders

        ]);
    }

    public function updateStatus($orderNumber, Request $request)
    {
        $orderTracking = Order::where('orderNumber', $orderNumber)->first();

        if(!$orderTracking)
        {
            return response()->json([
                'success' => false,
                'message' => 'No existe una orden con este número'
            ]);
        }

        $orderTracking->status = $request->status;

        $orderTracking->save();

        $pendingOrders =  Order::where('delivered', false)->get()->toArray();
        //Emit event
        broadcast(new OrderTrackingUpdated($orderTracking));
        broadcast(new OrderTrackingUpdatedForKitchen($pendingOrders));
        

        return response()->json([
            'success' => true,
            'message' => 'Orden actualizada correctamente!'
        ]);
    }   
   

}

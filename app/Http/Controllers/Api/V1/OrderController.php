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
use App\Services\AddressService;

class OrderController extends Controller
{

    public function history($client)
    {
        $clientOrders = Order::where('clientCode', $client)
                            ->orderBy('created_at','DESC')
                            ->get();   

        return response()->json(['success' => true, 'data' => $clientOrders]);                                                                                                                                                        
    }

    public function orderDetailHistory($orderNumber)
    {
        $order = Order::where('orderNumber', $orderNumber)->first();

        return response()->json(['succes' => true, 'data' => $order]);
    }

    public function store(Request $request)
    {
        $order = (new OrderService())->createOrder($request);
       
        $items = collect($request->items);

        $items = $items->map( function ($item){
            return [
                'id' => $item['idproducto'],
                'name' => $item['referencia'],
                'quantity' => $item['cantidad'],
                'price' => $item['precio']
            ];
        });
        
        $client = (new ClientService())->getClient($order->data['codcliente']);

        $orderBody = [
            "orderNumber"=> $order->data['codigo'],
            "clientCode" => $order->data['codcliente'],
            "clientName"=> $client->data['nombre'],
            "phone"=> $client->data['telefono1'],
            "address"=> $request->direccionorden,
            "addressId" => $request->direccionid,
            "orderDetail"=> $items,
            "orderType" => $request->tipoorden,
            "paymentMethod" => $request->codpago,
            "status" => 'En espera de validación'
        ];

        $_order = Order::create($orderBody);

        broadcast(new OrderCreated($_order));

        return response()->json([
            'success' => $order->success,
            'order' => [
                'products' => $items,
                'address' => (new AddressService())->getAddress($request->direccionid),
                'paymentMethod' => $_order->paymentMethod,
                'status' => $_order->status,
                'orderNumber' => $_order->orderNumber
            ],
            
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

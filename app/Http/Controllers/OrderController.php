<?php

namespace App\Http\Controllers;

use App\Services\FacturascriptService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $order = (new OrderService())->createInvoice($request);

        return response()->json([
            'success' => $order->success,
            'message' => $order->message,
            'data' => $order->data
        ]);
    }



}

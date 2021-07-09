<?php

namespace App\Http\Controllers;

use App\Events\OrderTrackingUpdated;
use App\Models\Tracking;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function store(Request $request)
    {
        Tracking::create($request->all());
    }

    public function changeStatus($orderNumber, Request $request)
    {
        $orderTracking = Tracking::where('orderNumber', $orderNumber)->first();

        if(!$orderTracking)
        {
            return response()->json([
                'success' => false,
                'message' => 'No existe una orden con este número'
            ]);
        }

        $orderTracking->status = $request->status;

        $orderTracking->save();

        //Emit event
        broadcast(new OrderTrackingUpdated($orderTracking));

        return response()->json([
            'success' => true,
            'message' => 'Orden actualizada correctamente!'
        ]);
    }   
}

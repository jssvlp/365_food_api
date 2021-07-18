<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderTrackingUpdatedForKitchen implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private $orders;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $orders)
    {
        $this->orders = $orders;
    }

    public function broadcastWith()
    {
        return [
            'orders' => $this->orders
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('kitchen');
    }
}

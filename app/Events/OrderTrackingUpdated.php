<?php

namespace App\Events;

use App\Models\Order;
use App\Models\Tracking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderTrackingUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private $tracking;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Tracking $tracking)
    {
        $this->tracking = $tracking;
    }

    public function broadcastWith()
    {
        return [
            'tracking' => $this->tracking
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('tracking.'.$this->tracking->orderNumber);
    }
}

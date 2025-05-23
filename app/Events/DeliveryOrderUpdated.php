<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event for broadcasting delivery order updates in real-time.
 */
class DeliveryOrderUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // The delivery order instance to broadcast
    public $order;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $order  The delivery order object or array
     */
    public function __construct($order)
    {
        // Log event firing for debugging
        logger('event fired..');
        $this->order = $order;
    }

    /**
     * Get the channels the event should broadcast on.
     * Uses a private channel specific to the order.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Log the channel name for debugging
        logger('Broadcasting on: orders.' . $this->order->id);
        // Broadcast on a private channel for the specific order
        return [
            new PrivateChannel('orders.' . $this->order->id),
        ];
    }

    /**
     * Name the broadcast event as 'DeliveryOrderUpdated'.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'DeliveryOrderUpdated';
    }
}

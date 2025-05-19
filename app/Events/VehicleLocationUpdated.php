<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
class VehicleLocationUpdated implements ShouldBroadcastNow
{
    public $coords;

    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct($coords)
    {
        // dd($coords);
        $this->coords = $coords;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        logger('vehicle.' . $this->coords->vehicle_id);
        return [
            new PrivateChannel('vehicle.' . $this->coords->vehicle_id),
        ];
    }

    public function broadcastWith()
    {
        return [
            'latitude' => $this->coords->latitude,
            'longitude' => $this->coords->longitude,
            'vehicle_id' => $this->coords->vehicle_id,
        ];
    }
    public function broadcastAs()
    {
        return 'TrackingLive';
    }
}

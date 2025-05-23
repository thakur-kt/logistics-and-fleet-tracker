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

/**
 * Event for broadcasting vehicle location updates in real-time.
 */
class VehicleLocationUpdated implements ShouldBroadcastNow
{
    // Coordinates object containing latitude, longitude, and vehicle_id
    public $coords;

    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  object  $coords  Object with latitude, longitude, and vehicle_id
     */
    public function __construct($coords)
    {
        // Assign the coordinates to the event property
        $this->coords = $coords;
    }

    /**
     * Get the channels the event should broadcast on.
     * Uses a private channel specific to the vehicle.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Log the channel name for debugging
        logger('vehicle.' . $this->coords->vehicle_id);
        // Broadcast on a private channel for the specific vehicle
        return [
            new PrivateChannel('vehicle.' . $this->coords->vehicle_id),
        ];
    }

    /**
     * Data to broadcast with the event.
     *
     * @return array
     */
    public function broadcastWith()
    {
        // Return latitude, longitude, and vehicle_id as broadcast data
        return [
            'latitude' => $this->coords->latitude,
            'longitude' => $this->coords->longitude,
            'vehicle_id' => $this->coords->vehicle_id,
        ];
    }

    /**
     * Name the broadcast event as 'TrackingLive'.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'TrackingLive';
    }
}

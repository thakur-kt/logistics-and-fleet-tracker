<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\DeliveryOrder;
use App\Models\User;
class AutoAssignDelivery implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $order;

    public function __construct(DeliveryOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // php artisan queue:work redis
        logger("Order {$this->order->id} auto-assigned starts");

        // Criteria: Find the nearest available driver (or any logic you define)
    
        // We can improve assignment logic using:
// Proximity (via coordinates and Haversine formula)
// Driver load (number of assigned deliveries)
// Driver rating or past performance
// Time window availability

        $availableDriver = User::role('driver')
            ->orderBy('created_at') // fair distribution
            ->first();

        if ($availableDriver) {
            // $this->order->update([
            //     'driver_id' => $availableDriver->id,
            //     'status' => 'assigned',
            // ]);
            $this->order->driver_id = $availableDriver->id;
            $this->order->status = 'assigned';
            $this->order->save();
            // $availableDriver->update([
            //     'status' => 'assigned',
            //     'last_assigned_at' => now(),
            // ]);

            logger("Order {$this->order->id} auto-assigned to driver {$availableDriver->id}");
        } else {
            logger("No available driver for order {$this->order->id}");
        }
    }
}

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
     * The delivery order instance to be assigned.
     *
     * @var \App\Models\DeliveryOrder
     */
    public $order;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\DeliveryOrder  $order
     * @return void
     */
    public function __construct(DeliveryOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     * Attempts to auto-assign the delivery order to an available driver.
     *
     * @return void
     */
    public function handle(): void
    {
        // php artisan queue:work redis
        logger("Order {$this->order->id} auto-assigned starts");

        // Criteria: Find the nearest available driver (or any logic you define)
        // You can improve assignment logic using:
        // - Proximity (via coordinates and Haversine formula)
        // - Driver load (number of assigned deliveries)
        // - Driver rating or past performance
        // - Time window availability

        // For now, assign to the earliest registered driver for fair distribution
        $availableDriver = User::role('driver')
            ->orderBy('created_at') // fair distribution
            ->first();

        if ($availableDriver) {
            // Assign the order to the available driver and update status
            $this->order->driver_id = $availableDriver->id;
            $this->order->status = 'assigned';
            $this->order->save();

            // Optionally, update driver status or last assigned time here
            // $availableDriver->update([
            //     'status' => 'assigned',
            //     'last_assigned_at' => now(),
            // ]);

            // Log successful assignment
            logger("Order {$this->order->id} auto-assigned to driver {$availableDriver->id}");
        } else {
            // Log if no driver is available for assignment
            logger("No available driver for order {$this->order->id}");
        }
    }
}

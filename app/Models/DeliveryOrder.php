<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * Model representing a delivery order.
 */
class DeliveryOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pickup_location',
        'dropoff_location',
        'scheduled_at',
        'status',
        'vehicle_id',
        'user_id',
        'driver_id'
    ];

    /**
     * Get the vehicle associated with this delivery order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }
    
    /**
     * Get the user who created this delivery order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the driver assigned to this delivery order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function driver() {
        return $this->belongsTo(User::class, 'driver_id');
    }
}

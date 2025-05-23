<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model representing a vehicle in the system.
 */
class Vehicle extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = [
        'user_id',
        'number_plate',
        'model',
        'status',
        'last_lat',
        'last_lng'
    ];

    /**
     * Get the driver (user) associated with this vehicle.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function driver()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all delivery orders assigned to this vehicle.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deliveryOrders()
    {
        return $this->hasMany(DeliveryOrder::class);
    }

    /**
     * Alias for deliveryOrders relationship.
     * Both methods return the same relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(DeliveryOrder::class);
    }
}

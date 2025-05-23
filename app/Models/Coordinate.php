<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model representing a coordinate (latitude/longitude) for a vehicle.
 */
class Coordinate extends Model
{
    // Allow mass assignment for these fields
    protected $fillable = [
        'vehicle_id',
        'latitude',
        'longitude',
    ];

    use HasFactory;

    /**
     * Get the vehicle associated with this coordinate.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }
}

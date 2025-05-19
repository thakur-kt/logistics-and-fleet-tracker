<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coordinate extends Model
{
    protected $fillable = [
        'vehicle_id',
        'latitude',
        'longitude',
    ];

    use HasFactory;

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }
}

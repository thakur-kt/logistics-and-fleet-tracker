<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
         'user_id'
    ];


    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }

    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Vehicle extends Model
{
   use HasFactory;

    protected $fillable = ['user_id', 'number_plate', 'model', 'status'];

    public function driver()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function deliveryOrders()
    {
        return $this->hasMany(DeliveryOrder::class);
    }

    public function orders()
{
    return $this->hasMany(DeliveryOrder::class);
}
}
    
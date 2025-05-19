<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

Broadcast::channel('orders.{orderId}', function ($user, $orderId) {
    // You can restrict access to the channel here
    return true; // Or $user->id === $someCondition
});
Broadcast::channel('vehicle.{id}', function ($user, $id) {
    return true; // Optional: add condition to restrict viewers
});
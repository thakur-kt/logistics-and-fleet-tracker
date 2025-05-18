<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->comment('Placed by');
            $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete('set null');
            $table->string('pickup_location');
            $table->string('dropoff_location');
            $table->timestamp('scheduled_at')->nullable();
            $table->enum('status', ['pending', 'assigned', 'delivered', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_orders');
    }
};

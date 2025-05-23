<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DeliveryOrder;

class DeliveryOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This will create 30 fake delivery orders using the DeliveryOrder factory.
     *
     * @return void
     */
    public function run(): void
    {
        // Generate and insert 30 delivery order records into the database
        DeliveryOrder::factory()->count(30)->create();
    }
}

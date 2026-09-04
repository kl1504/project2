<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Insert orders and capture IDs
        $order1Id = DB::table('orders')->insertGetId([
            'customer_id' => null,
            'guest_email' => 'guest1@example.com',
            'total_amount' => 300,
            'status' => 'completed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $order2Id = DB::table('orders')->insertGetId([
            'customer_id' => null,
            'guest_email' => 'guest2@example.com',
            'total_amount' => 150,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert order details linked to the real IDs
        DB::table('order_details')->insert([
            [
                'order_id' => $order1Id,
                'product_id' => 1,
                'quantity' => 2,
                'price' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => $order1Id,
                'product_id' => 2,
                'quantity' => 1,
                'price' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => $order2Id,
                'product_id' => 1,
                'quantity' => 3,
                'price' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

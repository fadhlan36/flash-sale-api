<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::insert([
            [
                'name' => 'Mechanical Keyboard',
                'price' => 500000,
                'stock' => 10,
            ],
            [
                'name' => 'Gaming Mouse',
                'price' => 300000,
                'stock' => 15,
            ],
            [
                'name' => 'Monitor 24 Inch',
                'price' => 2500000,
                'stock' => 5,
            ],
        ]);
    }
}

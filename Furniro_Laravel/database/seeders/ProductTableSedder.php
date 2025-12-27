<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTableSedder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         \DB::table('products')->insert(array (
            array (
                'id' => 1,
                'product' => 'Demo',
                'intro' => 'best choice',
                'price' => '3000000',
            ),
        ));
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Original products
            'Charbon Bois (OX)',
            'Caroube (T)',
            'Fleur Acacia (T)',
            'Liège (ST)',
            'Romarin (T)',
            'Tanin (T)',
            'BF/ST',
            'BI (m³)',
            'BO (m³)',
            // Additional products for contracts (with lowercase units as requested)
            'BF (st)',
            'Tanin (t)',
            'Fleur Acacia (t)',
            'Caroube (t)',
            'Romarin (t)',
            'PS (t)',
            'Liège (st)',
            'Laurier Sauce (t)',
            'Myrte (t)',
            'Callune (t)',
            'Thym (t)',
            'Bruyère (t)',
            'Lichen (t)',
            'Liège Mâle (t)',
            'Liège de Reproduction (t)',
            'Sauge (t)',
            'Lavande (t)',
            'Armoise (t)',
            'Origan (t)',
            'Alfa (t)',
            'Lentisque (t)',
            'Ciste (t)',
        ];

        foreach ($products as $productName) {
            Product::firstOrCreate(
                ['name' => $productName]
            );
        }
    }
}

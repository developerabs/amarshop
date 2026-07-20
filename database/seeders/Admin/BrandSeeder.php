<?php
namespace Database\Seeders\Admin;

use App\Models\Admin\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => "Nike",
                'slug' => 'nike',
                'image' => 'seeder/brands/nike.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Adidas",
                'slug' => 'adidas',
                'image' => 'seeder/brands/adidas.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Samsung",
                'slug' => 'samsung',
                'image' => 'seeder/brands/samsung.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Apple",
                'slug' => 'apple',
                'image' => 'seeder/brands/apple.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Sony",
                'slug' => 'sony',
                'image' => 'seeder/brands/sony.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Puma",
                'slug' => 'puma',
                'image' => 'seeder/brands/puma.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Gucci",
                'slug' => 'gucci',
                'image' => 'seeder/brands/gucci.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Prada",
                'slug' => 'prada',
                'image' => 'seeder/brands/prada.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Zara",
                'slug' => 'zara',
                'image' => 'seeder/brands/zara.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "H&M",
                'slug' => 'h-m',
                'image' => 'seeder/brands/hm.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Levi's",
                'slug' => "levis",
                'image' => "seeder/brands/levis.png",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Rolex",
                'slug' => "rolex",
                'image' => "seeder/brands/rolex.png",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        Brand::insert($brands);
    }
}
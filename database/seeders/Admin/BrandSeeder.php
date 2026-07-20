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
            ],
            [
                'name' => "Adidas",
                'slug' => 'adidas',
                'image' => 'seeder/brands/adidas.jpeg',
            ],
            [
                'name' => "Samsung",
                'slug' => 'samsung',
                'image' => 'seeder/brands/samsung.png',
            ],
            [
                'name' => "Apple",
                'slug' => 'apple',
                'image' => 'seeder/brands/apple.png',
            ],
            [
                'name' => "Sony",
                'slug' => 'sony',
                'image' => 'seeder/brands/sony.png',
            ],
            [
                'name' => "Puma",
                'slug' => 'puma',
                'image' => 'seeder/brands/puma.png',
            ],
            [
                'name' => "Gucci",
                'slug' => 'gucci',
                'image' => 'seeder/brands/gucci.png',
            ],
            [
                'name' => "Prada",
                'slug' => 'prada',
                'image' => 'seeder/brands/prada.jpeg',
            ],
            [
                'name' => "Zara",
                'slug' => 'zara',
                'image' => 'seeder/brands/zara.png',
            ],
            [
                'name' => "H&M",
                'slug' => 'h-m',
                'image' => 'seeder/brands/hm.png',
            ],
            [
                'name' => "Levi's",
                'slug' => "levis",
                'image' => "seeder/brands/levis.png",
            ],
            [
                'name' => "Rolex",
                'slug' => "rolex",
                'image' => "seeder/brands/rolex.png",
            ],
        ];
        Brand::insert($brands);
    }
}
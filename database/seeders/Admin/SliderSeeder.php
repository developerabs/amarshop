<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Slider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sliders = [
            [
                'title' => 'Summer Sale',
                'description' => 'Get up to 50% off on selected items.',
                'image' => 'seeder/sliders/1.jpg',
                'button_text' => 'Shop Now',
                'button_link' => '/allproducts',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'New Arrivals',
                'description' => 'Check out the latest products in our store.',
                'image' => 'seeder/sliders/2.jpg',
                'button_text' => 'Explore',
                'button_link' => '/allproducts',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Free Shipping',
                'description' => 'Enjoy free shipping on orders over $100.',
                'image' => 'seeder/sliders/3.jpg',
                'button_text' => 'Learn More',
                'button_link' => '/allproducts',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]; 
        Slider::insert($sliders); 
    }
}

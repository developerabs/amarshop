<?php
namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('brands')->insert([
            ['name' => 'Brand A', 'slug' => 'brand-a', 'description' => 'Description for Brand A', 'image' => 'brand-a.jpg', 'status' => true, 'sort_order' => 1, 'meta_title' => 'Brand A Meta Title', 'meta_keywords' => 'brand a, electronics, gadgets', 'meta_description' => 'Meta description for Brand A', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Brand B', 'slug' => 'brand-b', 'description' => 'Description for Brand B', 'image' => 'brand-b.jpg', 'status' => true, 'sort_order' => 2, 'meta_title' => 'Brand B Meta Title', 'meta_keywords' => 'brand b, books, literature', 'meta_description' => 'Meta description for Brand B', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Brand C', 'slug' => 'brand-c', 'description' => 'Description for Brand C', 'image' => 'brand-c.jpg', 'status' => true, 'sort_order' => 3, 'meta_title' => 'Brand C Meta Title', 'meta_keywords' => 'brand c, toys, games', 'meta_description' => 'Meta description for Brand C', 'created_at' => now(), 'updated_at' => now()], 
        ]);
    }
}
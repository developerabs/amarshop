<?php
namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('categories')->insert([
        //     ['name' => 'Electronics', 'slug' => 'electronics', 'parent_id' => null, 'description' => 'Electronic devices and gadgets', 'image' => 'electronics.jpg', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Books', 'slug' => 'books', 'parent_id' => null, 'description' => 'Fiction, non-fiction, and educational books', 'image' => 'books.jpg', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Toys & Games', 'slug' => 'toys-games', 'parent_id' => null, 'description' => 'Toys and games for children of all ages', 'image' => 'toys-games.jpg', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Fashion', 'slug' => 'fashion', 'parent_id' => null, 'description' => 'Clothing, shoes, and accessories', 'image' => 'fashion.jpg', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Home & Garden', 'slug' => 'home-garden', 'parent_id' => null, 'description' => 'Furniture, decor, and gardening supplies', 'image' => 'home-garden.jpg', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Sports & Outdoors', 'slug' => 'sports-outdoors', 'parent_id' => null, 'description' => 'Sporting goods and outdoor equipment', 'image' => 'sports-outdoors.jpg', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Health & Beauty', 'slug' => 'health-beauty', 'parent_id' => null, 'description' => 'Health products and beauty items', 'image' => 'health-beauty.jpg', 'created_at' => now(), 'updated_at' => now()],
        // ]);
    }
}
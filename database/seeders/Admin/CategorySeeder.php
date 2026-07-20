<?php
namespace Database\Seeders\Admin;

use App\Models\Admin\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $categories = [
            [
                'name' => "Women's Fashion",
                'icon' => 'ShoppingBag',
                'image' => 'seeder/categories/womens-fashion.jpeg',
                'subCategories' => [
                    [
                        'name' => 'Clothing',
                        'childCategories' => [
                            'Dresses',
                            'Tops',
                            'Skirts',
                            'Pants',
                        ],
                    ],
                    [
                        'name' => 'Shoes',
                        'childCategories' => [
                            'Heels',
                            'Flats',
                            'Boots',
                            'Sneakers',
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Electronic Devices',
                'icon' => 'Smartphone',
                'image' => 'seeder/categories/electronic-devices.jpeg',
                'subCategories' => [
                    [
                        'name' => 'Mobiles',
                        'childCategories' => [
                            'Smartphones',
                            'Feature Phones',
                            'Tablets',
                            'Refurbished Phones',
                        ],
                    ],
                    [
                        'name' => 'Laptops',
                        'childCategories' => [
                            'Gaming Laptops',
                            'MacBooks',
                            'Business Laptops',
                            '2-in-1 Laptops',
                        ],
                    ],
                    [
                        'name' => 'Desktops',
                        'childCategories' => [
                            'All-in-One',
                            'Gaming PCs',
                            'Mini PCs',
                            'Workstations',
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Home & Lifestyle',
                'icon' => 'Home',
                'image' => 'seeder/categories/home-lifestyle.jpeg',
                'subCategories' => [
                    [
                        'name' => 'Furniture',
                        'childCategories' => [
                            'Living Room',
                            'Bedroom',
                            'Kitchen',
                            'Office',
                        ],
                    ],
                    [
                        'name' => 'Decor',
                        'childCategories' => [
                            'Wall Art',
                            'Lighting',
                            'Rugs',
                            'Curtains',
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Health & Beauty',
                'icon' => 'Heart',
                'image' => 'seeder/categories/health-beauty.jpeg',
                'subCategories' => [
                    [
                        'name' => 'Skincare',
                        'childCategories' => [
                            'Moisturizers',
                            'Serums',
                            'Cleansers',
                            'Sunscreen',
                        ],
                    ],
                    [
                        'name' => 'Makeup',
                        'childCategories' => [
                            'Face',
                            'Eyes',
                            'Lips',
                            'Brushes',
                        ],
                    ],
                ],
            ],

            [
                'name' => "Men's Fashion",
                'icon' => 'User',
                'image' => 'seeder/categories/mens-fashion.jpeg',
                'subCategories' => [
                    [
                        'name' => 'Clothing',
                        'childCategories' => [
                            'Shirts',
                            'T-Shirts',
                            'Jeans',
                            'Suits',
                        ],
                    ],
                    [
                        'name' => 'Accessories',
                        'childCategories' => [
                            'Watches',
                            'Wallets',
                            'Belts',
                            'Sunglasses',
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Babies & Toys',
                'icon' => 'Baby',
                'image' => 'seeder/categories/babies-toys.jpeg',
                'subCategories' => [
                    [
                        'name' => 'Baby Gear',
                        'childCategories' => [
                            'Strollers',
                            'Car Seats',
                            'Walkers',
                        ],
                    ],
                    [
                        'name' => 'Toys',
                        'childCategories' => [
                            'Action Figures',
                            'Dolls',
                            'Puzzles',
                        ],
                    ],
                ],
            ],

            [
                'name' => 'TV & Home Appliances',
                'icon' => 'Tv',
                'image' => 'seeder/categories/tv-home-appliances.jpeg',
                'subCategories' => [
                    [
                        'name' => 'Televisions',
                        'childCategories' => [
                            'Smart TVs',
                            'OLED TVs',
                            'LED TVs',
                        ],
                    ],
                    [
                        'name' => 'Appliances',
                        'childCategories' => [
                            'Refrigerators',
                            'Washing Machines',
                            'Microwaves',
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Electronic Accessories',
                'icon' => 'Watch',
                'image' => 'seeder/categories/electronic-accessories.jpeg',
                'subCategories' => [
                    [
                        'name' => 'Wearables',
                        'childCategories' => [
                            'Smartwatches',
                            'Fitness Trackers',
                        ],
                    ],
                    [
                        'name' => 'Audio',
                        'childCategories' => [
                            'Headphones',
                            'Speakers',
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Grocery & Food',
                'icon' => 'Utensils',
                'image' => 'seeder/categories/grocery-food.jpeg',
                'subCategories' => [
                    [
                        'name' => 'Cooking Essentials',
                        'childCategories' => [
                            'Oil',
                            'Rice',
                            'Flour',
                        ],
                    ],
                    [
                        'name' => 'Snacks',
                        'childCategories' => [
                            'Chocolates',
                            'Chips',
                            'Biscuits',
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Sports & Outdoors',
                'icon' => 'Dumbbell',
                'image' => 'seeder/categories/sports-outdoors.jpeg',
                'subCategories' => [
                    [
                        'name' => 'Exercise',
                        'childCategories' => [
                            'Treadmills',
                            'Dumbbells',
                            'Yoga Mats',
                        ],
                    ],
                    [
                        'name' => 'Outdoor',
                        'childCategories' => [
                            'Camping',
                            'Cycling',
                            'Fishing',
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Books & Stationery',
                'icon' => 'Book',
                'image' => 'seeder/categories/books-stationery.jpeg',
                'subCategories' => [
                    [
                        'name' => 'Books',
                        'childCategories' => [
                            'Fiction',
                            'Non-Fiction',
                            'Academic',
                        ],
                    ],
                    [
                        'name' => 'Stationery',
                        'childCategories' => [
                            'Pens',
                            'Notebooks',
                            'Art Supplies',
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Automotive',
                'icon' => 'Car',
                'image' => 'seeder/categories/automotive.jpeg',
                'subCategories' => [
                    [
                        'name' => 'Car Accessories',
                        'childCategories' => [
                            'Interior',
                            'Exterior',
                            'Electronics',
                        ],
                    ],
                    [
                        'name' => 'Motorbike',
                        'childCategories' => [
                            'Helmets',
                            'Riding Gear',
                            'Parts',
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Watches & Accessories',
                'icon' => 'Watch',
                'image' => 'seeder/categories/watches-accessories.jpeg',
                'subCategories' => [
                    [
                        'name' => "Men's Watches",
                        'childCategories' => [
                            'Luxury',
                            'Casual',
                            'Sports',
                        ],
                    ],
                    [
                        'name' => "Women's Watches",
                        'childCategories' => [
                            'Fashion',
                            'Classic',
                            'Jewelry',
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Bags & Luggage',
                'icon' => 'Briefcase',
                'image' => 'seeder/categories/bags-luggage.jpeg',
                'subCategories' => [
                    [
                        'name' => 'Backpacks',
                        'childCategories' => [
                            'Laptop Bags',
                            'School Bags',
                            'Travel',
                        ],
                    ],
                    [
                        'name' => 'Handbags',
                        'childCategories' => [
                            'Tote Bags',
                            'Clutches',
                            'Wallets',
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Tools & Hardware',
                'icon' => 'Wrench',
                'image' => 'seeder/categories/tools-hardware.jpeg',
                'subCategories' => [
                    [
                        'name' => 'Hand Tools',
                        'childCategories' => [
                            'Screwdrivers',
                            'Wrenches',
                            'Hammers',
                        ],
                    ],
                    [
                        'name' => 'Power Tools',
                        'childCategories' => [
                            'Drills',
                            'Saws',
                            'Grinders',
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Pet Supplies',
                'icon' => 'PawPrint',
                'image' => 'seeder/categories/pet-supplies.jpeg',
                'subCategories' => [
                    [
                        'name' => 'Dog Supplies',
                        'childCategories' => [
                            'Food',
                            'Toys',
                            'Grooming',
                        ],
                    ],
                    [
                        'name' => 'Cat Supplies',
                        'childCategories' => [
                            'Litter',
                            'Food',
                            'Scratchers',
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Office Supplies',
                'icon' => 'Briefcase',
                'image' => 'seeder/categories/office-supplies.jpeg',
                'subCategories' => [
                    [
                        'name' => 'Furniture',
                        'childCategories' => [
                            'Desks',
                            'Chairs',
                            'Storage',
                        ],
                    ],
                    [
                        'name' => 'Stationery',
                        'childCategories' => [
                            'Paper',
                            'Pens',
                            'Organizers',
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Music & Instruments',
                'icon' => 'Tv',
                'image' => 'seeder/categories/music-instruments.jpeg',
                'subCategories' => [
                    [
                        'name' => 'Instruments',
                        'childCategories' => [
                            'Guitars',
                            'Keyboards',
                            'Drums',
                        ],
                    ],
                    [
                        'name' => 'Audio Equipment',
                        'childCategories' => [
                            'Microphones',
                            'Interfaces',
                            'Headphones',
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Garden & Outdoor',
                'icon' => 'Home',
                'image' => 'seeder/categories/garden-outdoor.jpeg',
                'subCategories' => [
                    [
                        'name' => 'Plants',
                        'childCategories' => [
                            'Seeds',
                            'Flowers',
                            'Trees',
                        ],
                    ],
                    [
                        'name' => 'Tools',
                        'childCategories' => [
                            'Mowers',
                            'Shovels',
                            'Hose',
                        ],
                    ],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {

            $parent = Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'level' => 0,
                'parent_id' => null,
                'icon' => $categoryData['icon'],
                'image' => $categoryData['image'],
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($categoryData['subCategories'] as $subCategoryData) {

                $sub = Category::create([
                    'name' => $subCategoryData['name'],
                    'slug' => Str::slug($parent->name . '-' . $subCategoryData['name']),
                    'level' => 1,
                    'parent_id' => $parent->id,
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($subCategoryData['childCategories'] as $childCategory) {

                    Category::create([
                        'name' => $childCategory,
                        'slug' => Str::slug($parent->name . '-' . $sub->name . '-' . $childCategory),
                        'level' => 2,
                        'parent_id' => $sub->id,
                        'status' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
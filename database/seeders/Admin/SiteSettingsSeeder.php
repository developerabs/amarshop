<?php
namespace Database\Seeders\Admin;

use App\Models\Admin\SiteSettings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['key' => 'site_name', 'value' => 'AmarShop', 'group' => 'general', 'type' => 'string'],
            ['key' => 'site_title', 'value' => 'AmarShop - Your One Stop Shop', 'group' => 'general', 'type' => 'string'],
            ['key' => 'site_description', 'value' => 'Welcome to AmarShop, Your one-stop shop for all your needs. We offer a wide range of products at competitive prices.', 'group' => 'general', 'type' => 'text'],
            ['key' => 'site_logo', 'value' => 'seeder/logo.jpeg', 'group' => 'general', 'type' => 'string'],
            ['key' => 'site_favicon', 'value' => 'seeder/favicon.png', 'group' => 'general', 'type' => 'string'],
            ['key' => 'site_email', 'value' => 'info@amarshop.com', 'group' => 'general', 'type' => 'string'],
            ['key' => 'site_phone', 'value' => '+1 (123) 456-7890', 'group' => 'general', 'type' => 'string'],
            ['key' => 'site_address', 'value' => '123 Main Street, City, State 12345', 'group' => 'general', 'type' => 'string'],
            ['key' => 'free_shipping_amount', 'value' => '1000', 'group' => 'general', 'type' => 'string'],
            ['key' => 'copyright_text', 'value' => '© 2026 AmarShop. All rights reserved.', 'group' => 'general', 'type' => 'string'],
        ];
        SiteSettings::insert($data);
    }
}
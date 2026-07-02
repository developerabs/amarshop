<?php
namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('site_settings')->insert([
            'site_name' => 'AmarShop',
            'site_title' => 'AmarShop - Your One Stop Shop',
            'site_description' => 'Welcome to AmarShop, your one-stop shop for all your needs. We offer a wide range of products at competitive prices.',
            'site_logo' => 'logo.png',
            'site_favicon' => 'favicon.ico',
            'site_email' => 'info@amarshop.com',
            'site_phone' => '+1 (123) 456-7890',
            'site_address' => '123 Main Street, City, State 12345',
            'copyright_text' => '© 2023 AmarShop. All rights reserved.'
        ]);
    }
}
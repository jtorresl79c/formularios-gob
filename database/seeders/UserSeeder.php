<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\settings;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    var $ACTIVO;

    public function __construct() {
        $this->ACTIVO = env("ACTIVO", "1");
        $this->INACTIVO = env("INACTIVO", "2");
    }

    public function run()
    {
        $u = new User;
        $u->name = 'Admin';
        $u->email = "admin@gmail.com";
        $u->password = bcrypt('admin123');
        $u->estado_id = $this->ACTIVO;
        $u->save();
        $u->assignRole('Admin');


        $settings = [
            ['key' => 'app_name', 'value' => 'Clustersig'],
            ['key' => 'app_logo', 'value' => 'appLogo/app-logo.png'],
            ['key' => 'app_small_logo', 'value' => 'appLogo/app-small-logo.png'],
            ['key' => 'favicon_logo', 'value' => 'appLogo/app-favicon-logo.png'],
            ['key' => 'default_language', 'value' => 'en'],
            ['key' => 'color', 'value' => 'theme-2'],
            ['key' => 'app_dark_logo', 'value' => 'appLogo/app-dark-logo.png'],
            ['key' => 'storage_type', 'value' => 'local'],
            ['key' => 'date_format', 'value' => 'M j, Y'],
            ['key' => 'time_format', 'value' => 'g:i A'],
            ['key' => 'roles', 'value' => 'User'],
            ['key' => 'google_calendar_enable', 'value' => 'off'],
            ['key' => 'captcha_enable', 'value' => 'off'],
            ['key' => 'transparent_layout', 'value' => 'on'],
            ['key' => 'dark_mode', 'value' => 'off'],
            ['key' => 'meta_image', 'value' => 'seo_image/meta_image.jpg'],
            ['key' => 'document_theme1', 'value' => 'document_theme/Stisla.png'],
            ['key' => 'document_theme2', 'value' => 'document_theme/Editor.png'],
            ['key' => 'app_setting_status', 'value' => 'on'],
            ['key' => 'menu_setting_status', 'value' => 'on'],
            ['key' => 'feature_setting_status', 'value' => 'on'],
            ['key' => 'faq_setting_status', 'value' => 'on'],
            ['key' => 'testimonial_setting_status', 'value' => 'on'],
            ['key' => 'sidefeature_setting_status', 'value' => 'on'],
            ['key' => 'landing_page', 'value' => '1'],

        ];

        foreach ($settings as $setting) {
            settings::firstOrCreate($setting);
        }
    }
}

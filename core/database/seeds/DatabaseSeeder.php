<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('resellers')->insert([
            'api_token' => str_random(60),
            'name' => 'Pulse Platform',
            'domain' => '*',
            'active' => true,
            'logo' => '/assets/branding/horizontal-light.svg',
            'logo_square' => '/assets/branding/square.svg',
            'favicon' => '/assets/branding/favicon.ico',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('plans')->insert([
            'order' => 1,
            'reseller_id' => 1,
            'name' => 'Full Access [undeletable]',
            'price1' => 0,
            'price1_string' => '0',
            'active' => false,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('users')->insert([
            'is_reseller_id' => 1,
            'reseller_id' => 1,
            'plan_id' => 1,
            'name' => 'System Owner',
            'email' => 'info@example.com',
            'password' => bcrypt('welcome'),
            'api_token' => str_random(60),
            'confirmed' => 1,
            'role' => 'owner',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Categories
        $order = 10;

        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 1,
            'name' => 'bars_restaurants',
            'icon' => 'restaurant',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 1,
            'name' => 'art_design',
            'icon' => 'camera',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 1,
            'name' => 'shows_events',
            'icon' => 'headset',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 1,
            'name' => 'fashion_clothing',
            'icon' => 'shirt',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 1,
            'name' => 'household_interior',
            'icon' => 'home',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 1,
            'name' => 'health_fitness',
            'icon' => 'heart',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 1,
            'name' => 'beauty_esthetics',
            'icon' => 'rose',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 1,
            'name' => 'electronics_communication',
            'icon' => 'wifi',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 1,
            'name' => 'toys_games',
            'icon' => 'game-controller-b',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 1,
            'name' => 'travel_tourism',
            'icon' => 'pin',
            'active' => true
        ]);

        $order = 10;

        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 2,
            'name' => 'bars_restaurants',
            'icon' => 'restaurant',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 2,
            'name' => 'art_design',
            'icon' => 'camera',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 2,
            'name' => 'shows_events',
            'icon' => 'headset',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 2,
            'name' => 'fashion_clothing',
            'icon' => 'shirt',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 2,
            'name' => 'household_interior',
            'icon' => 'home',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 2,
            'name' => 'health_fitness',
            'icon' => 'heart',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 2,
            'name' => 'beauty_esthetics',
            'icon' => 'rose',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 2,
            'name' => 'electronics_communication',
            'icon' => 'wifi',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 2,
            'name' => 'toys_games',
            'icon' => 'game-controller-b',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 2,
            'name' => 'travel_tourism',
            'icon' => 'pin',
            'active' => true
        ]);

        // Scenario if
        DB::table('scenario_if')->insert([
            'sort' => 10,
            'name' => 'enters_region_of'
        ]);

        DB::table('scenario_if')->insert([
            'sort' => 20,
            'name' => 'exits_region_of'
        ]);

        DB::table('scenario_if')->insert([
            'sort' => 30,
            'name' => 'is_far_from'
        ]);

        DB::table('scenario_if')->insert([
            'sort' => 40,
            'name' => 'is_near'
        ]);

        DB::table('scenario_if')->insert([
            'sort' => 50,
            'name' => 'is_very_near'
        ]);

        // Scenario time
        DB::table('scenario_time')->insert([
            'sort' => 10,
            'name' => 'all_the_time'
        ]);

        DB::table('scenario_time')->insert([
            'sort' => 20,
            'name' => 'between_two_times'
        ]);

        // Scenario then
        DB::table('scenario_then')->insert([
            'sort' => 10,
            'name' => 'only_for_analytics',
            'active' => false
        ]);

        DB::table('scenario_then')->insert([
            'sort' => 20,
            'name' => 'show_image',
            'active' => false
        ]);

        DB::table('scenario_then')->insert([
            'sort' => 30,
            'name' => 'show_template',
            'active' => false
        ]);

        DB::table('scenario_then')->insert([
            'sort' => 40,
            'name' => 'open_url'
        ]);

        // Scenario when
        DB::table('scenario_day')->insert([
            'sort' => 10,
            'name' => 'every_day'
        ]);

        DB::table('scenario_day')->insert([
            'sort' => 20,
            'name' => 'between_two_dates'
        ]);

        DB::table('scenario_day')->insert([
            'sort' => 30,
            'name' => 'saturday_and_sunday'
        ]);

        DB::table('scenario_day')->insert([
            'sort' => 40,
            'name' => 'friday_and_saturday'
        ]);

        DB::table('scenario_day')->insert([
            'sort' => 50,
            'name' => 'monday_to_friday'
        ]);

        DB::table('scenario_day')->insert([
            'sort' => 60,
            'name' => 'sunday_to_thursday'
        ]);

        DB::table('scenario_day')->insert([
            'sort' => 70,
            'name' => 'monday'
        ]);

        DB::table('scenario_day')->insert([
            'sort' => 80,
            'name' => 'tuesday'
        ]);

        DB::table('scenario_day')->insert([
            'sort' => 90,
            'name' => 'wednesday'
        ]);

        DB::table('scenario_day')->insert([
            'sort' => 100,
            'name' => 'thursday'
        ]);

        DB::table('scenario_day')->insert([
            'sort' => 110,
            'name' => 'friday'
        ]);

        DB::table('scenario_day')->insert([
            'sort' => 120,
            'name' => 'saturday'
        ]);

        DB::table('scenario_day')->insert([
            'sort' => 130,
            'name' => 'sunday'
        ]);
    }
}

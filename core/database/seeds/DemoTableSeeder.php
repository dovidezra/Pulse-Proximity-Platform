<?php

use Illuminate\Database\Seeder;

class DemoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=DemoTableSeeder
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        // Users
        $limit_users = 20;

        for ($i = 0; $i < $limit_users; $i++) {
            DB::table('users')->insert([
                'reseller_id' => 1,
                'name' => $faker->name,
                'email' => $faker->unique()->email,
                'password' => bcrypt('welcome'),
                'confirmed' => 1,
                'role' => 'user',
                'logins' => $faker->numberBetween($min = 1, $max = 50),
                'last_ip' => $faker->ipv4(),
                'last_login' => $faker->dateTimeThisMonth($max = 'now'),
                'created_at' => $faker->dateTimeThisYear($max = '-1 months')
            ]);
        }

        // Members
        $limit_members = 50;

        for ($i = 0; $i < $limit_members; $i++) {
            DB::table('members')->insert([
                'reseller_id' => 1,
                'user_id' => 1,
                'name' => $faker->name,
                'email' => $faker->unique()->email,
                'password' => bcrypt('welcome'),
                'confirmed' => 1,
                'role' => 'member',
                'logins' => $faker->numberBetween($min = 1, $max = 50),
                'last_ip' => $faker->ipv4(),
                'last_login' => $faker->dateTimeThisMonth($max = 'now'),
                'created_at' => $faker->dateTimeThisYear($max = '-1 months')
            ]);
        }

        // Coupons
        $limit_coupons = 6;

        for ($i = 0; $i < $limit_coupons; $i++) {

            $templates = ['coupon01', 'coupon02', 'coupon03'];
            $template = $templates[mt_rand(0,2)];
            $template_config = Platform\Controllers\Coupons\EditController::loadTemplate($template);

            $coupon = new Platform\Models\Coupons\Coupon;

            $coupon->user_id = 1;
            $coupon->name  = 'Coupon ' . ($i + 1);
            $coupon->template = $template;
            $coupon->icon = url('templates/coupons/' . $template . '/icon.png');
            $coupon->redeem_code = mt_rand(1000, 9999);
            $coupon->can_be_redeemed_more_than_once = true;
            $coupon->valid_from_date = date('Y-m-d 00:00:00');
            $coupon->expiration_date = date('Y-m-d 23:59:59', strtotime(' + 3 months'));
            $coupon->redeemed_subject = trans('global.coupon_redeemed_subject');
            $coupon->redeemed_text = trans('global.coupon_redeemed_line1');

            foreach($template_config as $column => $value) {
              if (\Schema::hasColumn('coupons', $column)) $coupon->{$column} = $value;
            }

            $coupon->save();
        }

        // Stats
        $limit = 180;

        for ($i = 0; $i < $limit; $i++) {

            $_os = ['Android', 'iOS', 'Mac', 'Mac', 'Mac', 'Ubuntu', 'Google TV', 'Firefox OS', 'Chrome OS', 'Windows', 'Windows', 'Windows', 'Windows', 'Windows'];
            $os = $_os[mt_rand(0, count($_os) - 1)];

            $_client = ['Android Browser', 'Chrome', 'Chrome Mobile', 'Firefox', 'Internet Explorer', 'IE Mobile', 'Mobile Safari', 'Opera', 'Opera Mobile'];
            $client = $_client[mt_rand(0, count($_client) - 1)];

            $_device = ['Apple', 'Windows', 'Mobile'];
            $device = $_device[mt_rand(0, count($_device) - 1)];

            $coupon_id = mt_rand(1, $limit_coupons);
            $member_id = mt_rand(1, $limit_members);
            $redeem = mt_rand(1, 6);
            $redeem = ($redeem == 1) ? true : false;

            $coupon = \Platform\Models\Coupons\Coupon::find($coupon_id);

            if ($redeem) {
              $coupon->number_of_times_redeemed = $coupon->number_of_times_redeemed + 1;
              $coupon->save();
            }

            $member = \Platform\Models\Members\Member::find($member_id);

            $coupon_stat = new \Platform\Models\Analytics\CouponStat;

            $coupon_stat->user_id = 1;
            $coupon_stat->coupon_id = $coupon_id;
            $coupon_stat->member_id = $member_id;
            $coupon_stat->redeemed = $redeem;
            $coupon_stat->ip = $faker->ipv4();
            $coupon_stat->os = $os;
            $coupon_stat->client = $client;
            $coupon_stat->device = $device;
            $coupon_stat->country = $faker->countryCode;
            $coupon_stat->city = $faker->city;
            $coupon_stat->lat = $faker->latitude($min = -90, $max = 90);
            $coupon_stat->lng = $faker->longitude($min = -180, $max = 180);
            $coupon_stat->created_at = $faker->dateTimeThisMonth($max = 'now');

            $coupon_stat->save();
        }
    }
}
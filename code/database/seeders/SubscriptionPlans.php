<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPlans extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('subscription_plans')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('subscription_plans')->insert([
            [
                'dbPlanPrice' => '19.99',
                'vPlanType' => 'Monthly',
                'vSkuId' => 'pora_monthly',
            ],
            [
                'dbPlanPrice' => '44.97',
                'vPlanType' => 'Quarterly',
                'vSkuId' => 'pora_quarterly',
            ]
        ]);
    }
}

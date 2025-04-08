<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FunInterests extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('fun_interests')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('fun_interests')->insert([
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Reading',
                'vInterestLogo' => 'Reading_Icon.png',
            ],
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Biking',
                'vInterestLogo' => 'Biking_Icon.png',
            ],
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Walking',
                'vInterestLogo' => 'Walking_Icon.png',
            ],
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Running',
                'vInterestLogo' => 'Running_Icon.png',
            ],
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Cooking',
                'vInterestLogo' => 'Cooking_Icon.png',
            ],
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Movie',
                'vInterestLogo' => 'Movie_Icon.png',
            ],
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Shopping',
                'vInterestLogo' => 'Shopping_Icon.png',
            ],
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Camping',
                'vInterestLogo' => 'Camping_Icon.png',
            ],
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Hiking',
                'vInterestLogo' => 'Hiking_Icon.png',
            ],
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Video games',
                'vInterestLogo' => 'Video_Games_Icon.png',
            ],
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Crafting',
                'vInterestLogo' => 'Crafting_Icon.png',
            ],
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Knitting',
                'vInterestLogo' => 'Knitting_Icon.png',
            ],
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Sewing',
                'vInterestLogo' => 'Sewing_Icon.png',
            ],
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Sports',
                'vInterestLogo' => 'Sports_Icon.png',
            ],
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Fitness',
                'vInterestLogo' => 'Fitness_Icon.png',
            ],
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Clubbing',
                'vInterestLogo' => 'Clubbing_Icon.png',
            ],
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Religion/Spiritual',
                'vInterestLogo' => 'Religion/Spiritual_Icon.png',
            ],
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Travelling',
                'vInterestLogo' => 'Travelling_Icon.png',
            ],
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Dancing',
                'vInterestLogo' => 'Dancing_Icon.png',
            ],
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Photography',
                'vInterestLogo' => 'Photography_Icon.png',
            ],
            // [
            //     'vInterestUuId' => GetUuid(),
            //     'vInterestName' => 'Travel',
            //     'vInterestLogo' => 'Travel_Icon.png',
            // ],
            [
                'vInterestUuId' => GetUuid(),
                'vInterestName' => 'Art',
                'vInterestLogo' => 'Art_Icon.png',
            ],

        ]);
    }
}

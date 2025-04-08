<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class versionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('versions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('versions')->insert([
            'iVersionId' => 1,
            'fVersion' => 1.0
        ]);
    }
}

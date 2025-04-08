<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentPage extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('content_pages')->insert([
            'vPageUuid' => 'd20780a5-247b-4c91-bccb-1e78f427219f',
            'vPageName' => 'About Us',
            'vSlug' => 'about_us',
            'txContent' => '<h3>About Us</h3>',
            'iCreatedAt' => time(),
            'iUpdatedAt' => time()
        ]);
    }
}

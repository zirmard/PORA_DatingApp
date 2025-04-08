<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoveLanguages extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('love_languages')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('love_languages')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('love_languages')->insert([
            [
                'vLoveLanguageUuid' => 'b20780a5-247b-4c91-bccb-1e78f427219f',
                'vLoveLanguage' => 'Quality Time',
                'vLoveLanguageLogo' => 'Quality_Time_Icon.png',
            ],
            [
                'vLoveLanguageUuid' => 'a20780b6-247b-4c91-bccb-1e78f427219f',
                'vLoveLanguage' => 'Acts of Service',
                'vLoveLanguageLogo' => 'Act_Of_Service_Icon.png',
            ],
            [
                'vLoveLanguageUuid' => 'c20780c7-247b-4c91-bccb-1e78f427219f',
                'vLoveLanguage' => 'Gifts',
                'vLoveLanguageLogo' => 'Gift_Icon.png',
            ],
            [
                'vLoveLanguageUuid' => 'd20780d8-247b-4c91-bccb-1e78f427219f',
                'vLoveLanguage' => 'Words of Affirmation',
                'vLoveLanguageLogo' => 'Words_Of_Affirmation.png',
            ],
            [
                'vLoveLanguageUuid' => 'e20780e9-247b-4c91-bccb-1e78f427219f',
                'vLoveLanguage' => 'Physical Touch',
                'vLoveLanguageLogo' => 'Physical_Touch_Icon.png',
            ],
        ]);
    }
}

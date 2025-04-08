<?php

namespace Database\Seeders;


use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Country::truncate();

        $path = public_path('admin_assets/table_countries.json');
        $json = File::get($path);
        $todos = json_decode($json);

        foreach ($todos as $key => $value) {
            Country::create([
                "vCountryId" => $value->vCountryId,
                "vCountryName" => $value->vCountryName,
                "vLocalName" => $value->vLocalName,
                "vwebCode" => $value->vwebCode,
                "vDialingCode" => $value->vDialingCode,
                "vRegion" => $value->vRegion,
                "vContinent" => $value->vContinent,
                "vLatitude" => $value->vLatitude,
                "vLongitude" => $value->vLongitude,
                "vSurfaceArea" => $value->vSurfaceArea,
                "vPopulation" => $value->vPopulation,
                "eStatus" => $value->eStatus,
            ]);
        }

    }
}

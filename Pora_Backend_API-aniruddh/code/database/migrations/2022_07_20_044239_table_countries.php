<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TableCountries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vCountryId');
            $table->string('vCountryName');
            $table->string('vLocalName');
            $table->string('vwebCode');
            $table->string('vDialingCode');
            $table->string('vRegion');
            $table->string('vContinent');
            $table->string('vLatitude');
            $table->string('vLongitude');
            $table->string('vSurfaceArea');
            $table->string('vPopulation');
            $table->enum('eStatus',array('Inactive','Active'))->default('Inactive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}

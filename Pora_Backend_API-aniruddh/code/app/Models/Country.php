<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $table = 'countries';
    public $timestamps = false;
    public $fillable = ['vCountryId','vCountryName','vLocalName','vwebCode','vDialingCode','vRegion','vContinent','vLatitude','vLongitude','vSurfaceArea','vPopulation','eStatus'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CitiesRU extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'cities_ru';

    protected $fillable = [
        'geonameid',
        'name',
        'asciiname',
        'alternatenames',
        'latitude',
        'longitude',
        'feature_class',
        'feature_code',
        'country_code',
        'cc2',
        'admin1_code',
        'admin2_code',
        'admin3_code',
        'admin4_code',
        'population',
        'elevation',
        'dem',
        'timezone',
        'modification_date',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


}

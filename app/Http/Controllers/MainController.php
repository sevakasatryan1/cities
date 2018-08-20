<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\CitiesRU;
use Illuminate\Support\Facades\DB;
class MainController extends Controller

{

    public function index()

    {
        return view('autocomplete');
    }

    public function ajaxData(Request $request){

        $input = $request->all();
        $cities = CitiesRU::where('name','LIKE', $input['keyword'].'%')->select('id','name')->limit($input['count'])->get();

        $return = [];

        foreach ($cities as $city) {
            $return[] = array(
                'label' => $city['name'],
                'value' => $city['value'],
                'id'    => $city['id']
            );
        }
        return response()->json($return);

    }

    public function nearestCitiesByDB ($id) {

        $limit = 20;
        $center = CitiesRU::where('id',$id)->select('latitude','longitude')->first()->toArray();

        $nearestCities  = CitiesRU::select(DB::raw('id, name, latitude, longitude , ( 6367 * acos( cos( radians('.$center['latitude'].') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$center['longitude'].') ) + sin( radians('.$center['latitude'].') ) * sin( radians( latitude ) ) ) ) AS distance'))
//            ->having('distance', '<', 25)
            ->orderBy('distance')->limit($limit)
            ->get();
        return response()->json(['cities' => $nearestCities, 'center'=> $center]);
    }

}
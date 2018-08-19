<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\CitiesRU;

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
//     Nearest cities by DB because it almost the same as by map. Thanks geonames.org :) .
//     We can do it by alternative solution, find nearest with google maps and check them in own db after it show on map but it will work most slowly.
        $limit = 20;
        $center = CitiesRU::where('id',$id)->select('latitude','longitude')->first()->toArray();

        $nearestCities = CitiesRU::where('id','>',$id-10)->select('id','name','latitude','longitude')->limit($limit)->get()->toArray();
        $nearestCitiesCount = count($nearestCities);

        if($nearestCitiesCount < $limit) {
            $nearestCities = CitiesRU::where('id','>',$id-(10+$limit-$nearestCitiesCount))->select('id','name','latitude','longitude')->limit($limit)->get()->toArray();
        }

        return response()->json(['cities' => $nearestCities, 'center'=> $center]);
    }

}
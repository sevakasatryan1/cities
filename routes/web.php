<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/',array('as'=>'autocomplete.search','uses'=>'MainController@index'));

Route::get('autocomplete-ajax',array('as'=>'autocomplete.ajax','uses'=>'MainController@ajaxData'));
Route::get('nearest-cities/{id}',array('as'=>'nearest.ajax','uses'=>'MainController@nearestCitiesByDB'));
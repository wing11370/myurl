<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalculatorController;

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

Route::get('/', function () {
    return view('index');
});
Route::get('/getCSRFToken','CalculatorController@getCSRFToken');
Route::get('/calculate',"CalculatorController@getAllData");
Route::post('/calculate', "CalculatorController@startCalc");
Route::get('/database',"CalculatorController@getDataFromDB");
// Route::resource('/calculate',"CalculatorController");
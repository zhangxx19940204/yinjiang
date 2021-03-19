<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

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

//Route::group(['middleware' => ['page_info']], function () {
//
//    Route::get('/', 'home\HomeController@index');
//    Route::get('/about', 'home\HomeController@about');
//    Route::get('/brand', 'home\HomeController@brand');
//    Route::get('/product', 'home\HomeController@product');
//    Route::get('/store', 'home\HomeController@store');
//    Route::get('/join', 'home\HomeController@join');
//    Route::get('/news', 'home\HomeController@news');
//    Route::get('/team', 'home\HomeController@team');
//    Route::get('/message', 'home\HomeController@message');
//});


//Route::get('/new_detail/{id}', 'home\HomeController@new_detail');

Route::get('/', [home\HomeController::class,'index']);
Route::any('/createMessage', [home\MessageController::class,'create_message']);
Route::any('/get_createMessage', [home\MessageController::class,'create_message']);

Route::any('/get_message_count',[home\MessageController::class,'get_message_count']);


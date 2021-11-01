<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CookieAuthenticationController;


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

// Route::get('/', function () {
//     return view('welcome');
// });



//Route::post('api/auth/login', [CookieAuthenticationController::class, 'login']);
//Route::post('api/auth/logout', [CookieAuthenticationController::class, 'logout']);
//Route::get('api/auth/unauthenticated', [CookieAuthenticationController::class, 'unauthenticated'])->name('unauthenticated');


Route::get('{path?}', 'Controller@action')->where('path', '.*');







Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

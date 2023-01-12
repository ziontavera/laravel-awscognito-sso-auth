<?php

use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('/test', function () {
//     return view('test');
// });
// OAuth (Cognito)
Route::get('oauth2/login', [App\Http\Controllers\Auth\LoginController::class, 'redirectToExternalAuthServer']);                                  // Login button - Post to OAuth Server
Route::get('oauth2/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleExternalAuthCallback']);                                 // For OAuth2 Callback (Cognito)
Route::get('oauth2/logout', [App\Http\Controllers\Auth\LoginController::class, 'cognitoLogout'])->name('oauth-logout');                          // OAuth2 triggered logout (Cognito)
Route::get('oauth2/switch-account', [App\Http\Controllers\Auth\LoginController::class, 'cognitoSwitchAccount'])->name('oauth-switch-account');   // Logout and login to another account

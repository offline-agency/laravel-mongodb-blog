<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by bootstrap/app.php within a group which is
| assigned the "api" middleware group and the "api" URI prefix.
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

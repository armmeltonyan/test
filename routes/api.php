<?php

use App\Http\Controllers\v0\API\Image\ImageController;
use App\Http\Controllers\v0\API\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/image', [ImageController::class, 'store'])->name('image');
Route::post('/user', [UserController::class, 'store'])->name('user');

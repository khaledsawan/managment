<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\NoteController;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);




Route::middleware('auth:api')->group(function () {Route::middleware('userToekn')->group(
    function () {
        Route::group(['prefix' => 'note'], function () {
            Route::get('index', [NoteController::class, 'index']);
            Route::post('store', [NoteController::class, 'store']);
            Route::post('update', [NoteController::class, 'update']);
            Route::post('delete', [NoteController::class, 'delete']);
            Route::post('show', [NoteController::class, 'show']);
        });
    }
);});


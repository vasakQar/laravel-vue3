<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/data', function () {
    return response()->json([
        'message' => 'Hello from Laravel API2222!',
    ]);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

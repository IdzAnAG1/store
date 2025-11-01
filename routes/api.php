<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Models\Product;
use App\Http\Controllers\AuthLoginController;

Route::get('/test',function (){
    return response()->json([
        "test" => "successful"
    ]);
});

Route::prefix("v1")->group(function (){
    Route::get("categories", function (){
        return response()->json([
            "categories" => Category::all()
        ]);
    });
    Route::get("products", function (){
        return response()->json([
            "products" => Product::all()
        ]);
    });
    Route::middleware('auth:sanctum')
        ->get('user', [AuthLoginController::class, "GetUserByToken"]);

    Route::post('auth', [AuthLoginController::class, "login"]);
    Route::post('register', [AuthLoginController::class, "register"]);
});


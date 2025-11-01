<?php

use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
});

Route::prefix('auth')->group(function (){
    Route::get('/login', function (){
        return view('pages.login');
    })->name('login');
    Route::get('register', function() {
        return view('pages.register');
    })->name('register');

    Route::prefix('dashboard')->group(function (){
        Route::get('user', function (){
            return view('pages.user_page');
        })->name('dashboard.user');

        Route::middleware('role:admin')->group(function (){
            Route::get('/admin', fn () => view('admin.page'))->name('dashboard.admin');
            Route::get('/admin/products', fn () => view('admin.products'))->name('admin.products');
            Route::get('/admin/orders', fn () => view('admin.orders'))->name('admin.orders');
            Route::get('/admin/users', fn () => view('admin.users'))->name('admin.users');
        });

        Route::middleware('role:manager')->group(function (){
            Route::get('/manager', fn () => view('manager.page'))->name('dashboard.manager');
            Route::get('/manager/products', fn () => view('admin.users.index'))->name('admin.users');
        });

    });
});



Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');

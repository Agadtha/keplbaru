<?php
use App\Http\Controllers\api\ProductController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('home');
});

Route::get('/about', function () {
    return view('about');
});

Route::resource('products', ProductController::class);
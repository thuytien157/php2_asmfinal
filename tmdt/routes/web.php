<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\client\HomeController;


Route::get('/', [HomeController::class, 'index']);

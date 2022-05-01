<?php

use App\Http\Controllers\Site\LinkRedirectController;
use Illuminate\Support\Facades\Route;

Route::get('/{id}', LinkRedirectController::class)
    ->name('link.redirect');

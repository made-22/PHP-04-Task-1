<?php

use App\Http\Controllers\Api\V1\ShortLink\LinkCreationController;
use App\Http\Controllers\Api\V1\ShortLink\LinkDestroyController;
use App\Http\Controllers\Api\V1\ShortLink\LinkIndexController;
use App\Http\Controllers\Api\V1\ShortLink\LinkShowController;
use App\Http\Controllers\Api\V1\ShortLink\LinkUpdateController;
use App\Http\Controllers\Api\V1\Stat\StatIndexController;
use App\Http\Controllers\Api\V1\Stat\StatShowController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'links', 'as' => 'links.'], function () {
    Route::post('/', LinkCreationController::class)
        ->name('store');

    Route::patch('/{id}', LinkUpdateController::class)
        ->name('update');

    Route::delete('/{id}', LinkDestroyController::class)
        ->name('destroy');

    Route::get('/', LinkIndexController::class)
        ->name('index');

    Route::get('/{id}', LinkShowController::class)
        ->name('show');
});

Route::group(['prefix' => 'stats', 'as' => 'stats.'], function () {
    Route::get('/', StatIndexController::class)
        ->name('index');

    Route::get('/{id}', StatShowController::class)
        ->name('show');;
});

<?php

use Illuminate\Support\Facades\Route;
use Kaster\Cms\Controllers\PagesController;

Route::get('/{page?}', [PagesController::class, 'show'])
    ->name('page.show')
    ->where('page', '[a-zA-Z0-9\-]+');

<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware(['throttle:api'])
    ->as('api.v1')
    ->group(base_path('routes/v1.php'));

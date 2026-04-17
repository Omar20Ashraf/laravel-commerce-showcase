<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware(['throttle:api', 'extract-current-api-version'])
    ->as('api.v1')
    ->group(base_path('routes/v1.php'));

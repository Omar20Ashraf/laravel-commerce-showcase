<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->as('api.')
    ->group(base_path('routes/v1.php'));

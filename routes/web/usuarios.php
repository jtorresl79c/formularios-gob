<?php

use App\Http\Controllers\UserController;

Route::resource('usuarios', UserController::class)->middleware('can:usuarios.index');
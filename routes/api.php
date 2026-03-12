<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\PipelineController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/requests', [RequestController::class, 'index']);
Route::get('/pipelines/{id}', [PipelineController::class, 'show']);

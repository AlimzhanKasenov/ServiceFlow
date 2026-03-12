<?php

use App\Http\Controllers\Api\RequestMoveController;
use App\Http\Controllers\Api\TransitionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\PipelineController;

/*
|--------------------------------------------------------------------------
| API Роуты
|--------------------------------------------------------------------------
*/

Route::get('/requests', [RequestController::class, 'index']);
Route::get('/pipelines/{id}', [PipelineController::class, 'show']);
Route::post('/requests/{id}/move', [RequestController::class, 'move']);
Route::post('/requests/{request}/move', [RequestMoveController::class, 'move']);
Route::get('/stages/{stage}/transitions', [TransitionController::class, 'index']);

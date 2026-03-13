<?php

use App\Http\Controllers\Api\RequestMoveController;
use App\Http\Controllers\Api\TransitionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\PipelineController;

Route::get('/requests', [RequestController::class, 'index']);
Route::get('/pipelines/{id}', [PipelineController::class, 'show']);
Route::post('/requests/{serviceRequest}/move', [RequestController::class, 'move']);
Route::get('/stages/{stage}/transitions', [TransitionController::class, 'index']);
Route::post('/requests', [RequestController::class, 'store']);

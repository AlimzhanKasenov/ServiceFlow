<?php

use App\Http\Controllers\Api\PipelineController;
use App\Http\Controllers\Api\RequestController;

Route::get('/pipelines/{id}', [PipelineController::class, 'show']);
Route::get('/requests', [RequestController::class, 'index']);

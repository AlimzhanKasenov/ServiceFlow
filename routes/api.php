<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\CheckPermission;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\RequestMoveController;
use App\Http\Controllers\Api\RequestCommentController;
use App\Http\Controllers\Api\RequestActivityController;

use App\Http\Controllers\Api\PipelineController;
use App\Http\Controllers\Api\StageController;
use App\Http\Controllers\Api\TransitionController;

/*
|--------------------------------------------------------------------------
| ServiceFlow API
|--------------------------------------------------------------------------
*/

/*
| REQUESTS
*/
Route::prefix('requests')->group(function () {

    Route::get('/', [RequestController::class, 'index']);

    Route::post('/', [RequestController::class, 'store']);

    Route::get('/{id}', [RequestController::class, 'show']);

    Route::patch('/{serviceRequest}', [RequestController::class, 'update']);

    Route::post('/{req}/move', [RequestMoveController::class, 'move']);

    Route::post('/{serviceRequest}/assign', [RequestController::class, 'assign']);

    Route::get('/{id}/activities', [RequestActivityController::class, 'index']);

    Route::get('/{id}/comments', [RequestCommentController::class, 'index']);

    Route::post('/{id}/comments', [RequestCommentController::class, 'store']);

});

/*
| PIPELINES
*/
Route::prefix('pipelines')->group(function () {

    // получить pipeline
    Route::get('/{id}', [PipelineController::class, 'show']);

    // стадии pipeline
    Route::get('/{pipeline}/stages', [StageController::class, 'byPipeline']);

});


/*
| STAGES
*/
Route::prefix('stages')->group(function () {

    // доступные переходы стадий
    Route::get('/{stage}/transitions', [TransitionController::class, 'index']);

});


/*
| USERS
*/
Route::get('/users', [UserController::class, 'index']);


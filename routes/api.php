<?php

use App\Http\Controllers\Api\UserController;
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

    // список заявок
    Route::get('/', [RequestController::class, 'index']);

    // создание заявки
    Route::post('/', [RequestController::class, 'store']);

    // просмотр заявки
    Route::get('/{id}', [RequestController::class, 'show']);

    // назначение исполнителя
    Route::post('/{serviceRequest}/assign', [RequestController::class, 'assign']);

    // перемещение стадии
    Route::post('/{req}/move', [RequestMoveController::class, 'move']);

    // комментарии
    Route::get('/{id}/comments', [RequestCommentController::class, 'index']);
    Route::post('/{id}/comments', [RequestCommentController::class, 'store']);

    // история действий
    Route::get('/{id}/activities', [RequestActivityController::class, 'index']);

    // обновление заявки
    Route::patch('/{serviceRequest}', [RequestController::class, 'update']);

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


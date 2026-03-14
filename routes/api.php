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
|
| Основные REST API системы ServiceFlow
|
*/


/*
|--------------------------------------------------------------------------
| REQUESTS
|--------------------------------------------------------------------------
*/

Route::prefix('requests')->group(function () {

    /*
    | Список заявок
    */
    Route::get('/', [RequestController::class, 'index']);

    /*
    | Создание заявки
    */
    Route::post('/', [RequestController::class, 'store']);

    /*
    | Просмотр заявки
    */
    Route::get('/{id}', [RequestController::class, 'show']);


    /*
    | Назначение исполнителя
    */
    Route::post('/{serviceRequest}/assign', [RequestController::class, 'assign']);


    /*
    | Перемещение заявки по стадиям
    */
    Route::post('/{req}/move', [RequestMoveController::class, 'move']);


    /*
    | Комментарии заявки
    */
    Route::get('/{id}/comments', [RequestCommentController::class, 'index']);
    Route::post('/{id}/comments', [RequestCommentController::class, 'store']);


    /*
    | История действий заявки
    */
    Route::get('/{id}/activities', [RequestActivityController::class, 'index']);

});


/*
|--------------------------------------------------------------------------
| PIPELINES
|--------------------------------------------------------------------------
*/

Route::prefix('pipelines')->group(function () {

    /*
    | Получить pipeline
    */
    Route::get('/{id}', [PipelineController::class, 'show']);

    /*
    | Стадии pipeline
    */
    Route::get('/{pipeline}/stages', [StageController::class, 'byPipeline']);

});

Route::prefix('stages')->group(function () {

    /*
    | Доступные переходы стадий
    */
    Route::get('/{stage}/transitions', [TransitionController::class, 'index']);

});

Route::get('/users', [UserController::class, 'index']);

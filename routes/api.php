<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\RequestMoveController;
use App\Http\Controllers\Api\TransitionController;
use App\Http\Controllers\Api\PipelineController;
use App\Http\Controllers\Api\RequestCommentController;

/*
| Заявки
*/
Route::get('/requests', [RequestController::class, 'index']);
Route::post('/requests', [RequestController::class, 'store']);
Route::get('/requests/{id}', [RequestController::class, 'show']);

/*
| Перемещение заявки по стадиям
*/
Route::post('/requests/{req}/move', [RequestMoveController::class, 'move']);

/*
| Воронки
*/
Route::get('/pipelines/{id}', [PipelineController::class, 'show']);

/*
| Доступные переходы стадий
*/
Route::get('/stages/{stage}/transitions', [TransitionController::class, 'index']);

/*
| Комментарии
*/
Route::get('/requests/{id}/comments', [RequestCommentController::class, 'index']);
Route::post('/requests/{id}/comments', [RequestCommentController::class, 'store']);

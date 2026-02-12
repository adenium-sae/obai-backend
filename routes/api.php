<?php

use App\Http\Controllers\Api\AssistantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ConversationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/chat/{conversation}', [ChatController::class, 'sendMessage']);

Route::prefix("/conversations")->group(function () {
    Route::get("/", [ConversationController::class, "index"]);
    Route::post("/", [ConversationController::class, "store"]);
    Route::get("/{conversation}", [ConversationController::class, "show"]);
});

Route::prefix("assistants")->group(function () {
    Route::get("/", [AssistantController::class, "index"]);
});
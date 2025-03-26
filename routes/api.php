<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/user', [UserController::class, 'user']);
  Route::get('/chat', [ChatController::class, 'index']);
  Route::post('/chat', [ChatController::class, 'store']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

Route::middleware('auth:sanctum')->get('/chat', function (Request $request) {
  return [
      ['id' => 1, 'user' => $request->user()->name, 'message' => 'Halo, selamat datang di chat!'],
      ['id' => 2, 'user' => 'Bot', 'message' => 'Halo! Apa yang bisa dibantu?'],
  ];
});

Route::middleware('auth:sanctum')->post('/chat', function (Request $request) {
  $message = $request->input('message');
  $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
  $chatMessage = [
      'id' => 3,
      'user' => $request->user()->name,
      'message' => $message,
  ];
  event(new \App\Events\MessageSent($chatMessage));
  return $chatMessage;
});
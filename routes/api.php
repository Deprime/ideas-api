<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\{
  IdeaController,
};


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::namespace('Api')->group(function() {
  Route::prefix('v1')->group(function () {
    Route::prefix('ideas')->group(function () {
      Route::get('/',             [IdeaController::class, 'list']);
      Route::get('{idea_id}',     [IdeaController::class, 'get'])->whereNumber('idea_id');
      Route::post('/',            [IdeaController::class, 'create']);
      Route::put('{idea_id}',     [IdeaController::class, 'update'])->whereNumber('idea_id');
      Route::delete('{idea_id}',  [IdeaController::class, 'delete'])->whereNumber('idea_id');
      Route::patch('{idea_id}',   [IdeaController::class, 'restore'])->whereNumber('idea_id');
    });
  });
});

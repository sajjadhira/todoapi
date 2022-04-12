<?php

use App\Http\Controllers\CoinsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// 1|x0E84YyItxp6HxtCAdFJA87hFvdmPf23dKHeqKNq
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(
    ['middleware' => ['auth:sanctum']], function(){
        Route::get('/info', [AuthController::class, 'info']);
        Route::get('/todos', [TodosController::class, 'index']);
        Route::get('/todos/{id}', [TodosController::class, 'show']);
        Route::post('todos', [TodosController::class,'store']); 
        Route::post('todos/{id}', [TodosController::class,'update']); 
        Route::delete('todos/{id}', [TodosController::class,'destroy']); 
        Route::post('/logout', [AuthController::class, 'logout']);
    }
);

// Route::resource('coins', CoinsController::class);
Route::get('/todos/search/{name}', [TodosController::class, 'search']);
// Route::post('/coins', [CoinsController::class, 'store']);
<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::middleware('auth')->prefix('api')->group(function () {

	Route::get('/tasks', [TaskController::class, 'list']);
	Route::post('/tasks', [TaskController::class, 'create']);
	Route::delete('/tasks/{task}', [TaskController::class, 'delete']);
	Route::post('/tasks/{task}/complete', [TaskController::class, 'complete']);

});

require __DIR__.'/auth.php';

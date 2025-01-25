<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:sanctum']);

Route::group(['controller' => TaskController::class, 'prefix' => 'tasks', 'middleware' => 'auth:sanctum'], function () {
    Route::get('','index');          // List all tasks
    Route::post('','store');         // Create a new task
    Route::get('/{id}','show');      // Show a specific task
    Route::patch('/{id}','update');  // Update a task
    Route::delete('/{id}','destroy'); // Delete a task

    // Custom route for filtering tasks by status
    Route::get('/filter/{status}', 'filterByStatus');  // Filter tasks by status (completed, not completed)
});

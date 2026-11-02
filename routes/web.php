<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

// Redirect root to todos index
Route::get('/', [TodoController::class, 'index'])->name('todos.index');

// Todo CRUD routes
Route::prefix('todos')->name('todos.')->group(function () {
    Route::post('/', [TodoController::class, 'store'])->name('store');
    Route::get('/{todo}', [TodoController::class, 'show'])->name('show');
    Route::put('/{todo}', [TodoController::class, 'update'])->name('update');
    Route::patch('/{todo}/toggle', [TodoController::class, 'toggle'])->name('toggle');
    Route::delete('/{todo}', [TodoController::class, 'destroy'])->name('destroy');
});

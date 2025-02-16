<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/contacts', [ContactController::class, 'index']);
Route::post('/contacts/store', [ContactController::class, 'store']);
Route::get('/contacts/list', [ContactController::class, 'list']);
Route::get('/contacts/search', [ContactController::class, 'search']);
// Route::delete('/contacts/delete/{id}', [ContactController::class, 'destroy']);
Route::post('/contacts/delete', [ContactController::class, 'delete'])->name('contacts.delete');
Route::get('/contacts/{id}/edit', [ContactController::class, 'edit']);
Route::post('/contacts/update', [ContactController::class, 'update'])->name('contacts.update');

<?php

use App\Http\Controllers\VigenereController;
use Illuminate\Support\Facades\Route;

Route::get('/', [VigenereController::class, 'index'])->name('vigenere.index');
Route::post('/process', [VigenereController::class, 'process'])->name('vigenere.process');
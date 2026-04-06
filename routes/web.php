<?php

use App\Http\Controllers\VigenereController;
use App\Http\Controllers\KasiskiController;
use App\Http\Controllers\FrequencyAnalysisController;
use Illuminate\Support\Facades\Route;

Route::get('/', [VigenereController::class, 'index'])->name('vigenere.index');
Route::post('/process', [VigenereController::class, 'process'])->name('vigenere.process');

Route::get('/kasiski', [KasiskiController::class, 'index'])->name('kasiski.index');
Route::post('/kasiski/analyze', [KasiskiController::class, 'analyze'])->name('kasiski.analyze');

Route::get('/frequency', [FrequencyAnalysisController::class, 'index'])->name('frequency.index');
Route::post('/frequency/analyze', [FrequencyAnalysisController::class, 'analyze'])->name('frequency.analyze');
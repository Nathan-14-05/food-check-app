<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Maintenant, l'adresse racine redirige vers ton Controller
Route::get('/', [ProductController::class, 'index']);

// Tes nouvelles routes
Route::get('/aliments', [ProductController::class, 'index']);
Route::get('/aliments/nouveau', [ProductController::class, 'create']);
Route::post('/aliments', [ProductController::class, 'store']);
Route::get('/api/product/{barcode}', [ProductController::class, 'getInfoFromApi']);
Route::delete('/aliments/{id}', [ProductController::class, 'destroy']);

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// --- ROUTES PUBLIQUES ---
Route::get('/', function () {
    return view('welcome');
});

// --- ROUTES PROTÉGÉES (Connexion obligatoire) ---
Route::middleware('auth')->group(function () {

    // 1. Ton Dashboard (Page principale)
    Route::get('/dashboard', [ProductController::class, 'index'])->name('dashboard');

    // 1.5 Création d'une nouvelle liste d'aliments
    Route::post('/food-lists', [ProductController::class, 'storeList'])->name('food-lists.store');

    // 2. Affichage du formulaire de scan (Ta page 404 actuelle)
    Route::get('/aliments/nouveau', [ProductController::class, 'create'])->name('products.create');

    // 3. Enregistrement du produit après le scan
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');

    // 4. Suppression d'un aliment
    // Note : On utilise l'URL /aliments/{id} pour correspondre à ton formulaire dans l'index
    Route::delete('/aliments/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // 5. Routes du profil utilisateur (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Charge les routes d'authentification (login, register, logout)
require __DIR__.'/auth.php';

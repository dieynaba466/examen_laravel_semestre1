<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LivreController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategorieController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route d'accueil
Route::get('/', function () {
    return view('auth.login');
});

// Route de connexion
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Redirection en fonction du rôle de l'utilisateur après authentification
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'client') {
        return redirect('/catalogue');
    } elseif ($user->role === 'gestionnaire') {
        return redirect('/statistiques');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes pour les utilisateurs authentifiés
Route::middleware('auth')->group(function () {
    Route::get('/search', [SearchController::class, 'search'])->name('search');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/logout', [ProfileController::class, 'destroy'])->name('logout');
});

// Inclusion des routes d'authentification Breeze
require __DIR__.'/auth.php';

// Routes pour les gestionnaires (prefixe "admin")
Route::middleware(['auth', 'role:gestionnaire'])->prefix('admin')->group(function () {
    // Gestion des livres
    Route::resource('livres', LivreController::class);
    // Gestion des commandes (except store)
    Route::resource('commandes', CommandeController::class)->except(['store']);
});

// Routes spécifiques selon le rôle
Route::middleware(['auth'])->group(function () {
    // *** Gestionnaire ***
    Route::middleware(['role:gestionnaire'])->group(function () {
        // Gestion des livres
        Route::resource('livres', LivreController::class)->except(['show']);

        // Gestion des commandes
        Route::resource('commandes', CommandeController::class)->except(['store']);
        Route::get('/commandes/{commande}/generer-facture', [CommandeController::class, 'genererFacture'])->name('commandes.genererFacture');
        Route::get('/commandes/{commande}/paye', [CommandeController::class, 'commandesPayees'])->name('commandes.commandandepaye');
        Route::get('/commandes/{commande}', [CommandeController::class, 'show'])->name('commandes.show');
        Route::get('/commandes/{commande}/edit', [CommandeController::class, 'edit'])->name('commandes.edit');
        Route::patch('/commandes/{commande}/block', [CommandeController::class, 'block'])->name('commandes.block');
        Route::post('/commandes/{commande}/paiement', [CommandeController::class, 'enregistrerPaiement'])->name('commandes.enregistrerPaiement');

        // Statistiques
        Route::get('/statistiques', [DashboardController::class, 'statistiques'])->name('stat.index');

        // Archivage de livres
        Route::post('/livres/{livre}/archive', [LivreController::class, 'archive'])->name('livres.archive');
        Route::post('/livres/{livre}/restore', [LivreController::class, 'restore'])->name('livres.restore');
        Route::get('/livres/archives', [LivreController::class, 'archived'])->name('livres.archived');
    });

    // *** Client ***
    Route::middleware(['role:client'])->group(function () {
        // Catalogue pour le client
        Route::get('/catalogue', [LivreController::class, 'catalogue'])->name('catalogue');

        // Commandes pour le client
        Route::post('/commandes', [CommandeController::class, 'store'])->name('commandes.store');
        Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');

        // Panier
        Route::post('/panier/{livre}', [PanierController::class, 'ajouter'])->name('panier.ajouter');
        Route::get('/panier', [PanierController::class, 'index'])->name('panier.index');
        Route::get('/panier/valider', [PanierController::class, 'valider'])->name('panier.valider');
        Route::put('/panier/update/{id}', [PanierController::class, 'update'])->name('panier.update');
        Route::delete('/panier/annuler', [PanierController::class, 'annuler'])->name('panier.annuler');
        Route::get('/categorie/{id}', [CategorieController::class, 'show'])->name('categorie.show');

    });
});

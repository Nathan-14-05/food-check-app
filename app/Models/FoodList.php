<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Le Modèle FoodList représente le "panier" ou le "tiroir" de l'utilisateur.
 * C'est l'élément central qui permet de ne pas mélanger les produits
 * de différents utilisateurs ou de différentes listes (Frigo, Courses, etc.).
 */
class FoodList extends Model
{

    /**
     * On autorise seulement le 'name' et le 'user_id' à être remplis.
     * C'est une sécurité "Mass Assignment" : on empêche d'injecter des données non autorisées dans la base de données.
     */
    protected $fillable = ['name', 'user_id'];

    public function user() { return $this->belongsTo(User::class); }


    /**
     * RELATION : hasMany (A plusieurs)
     * C'est ici que l'ORM Eloquent montre sa puissance.
     * Logique : "Une liste contient plusieurs produits".
     * En code, cela permet de faire : $list->products pour récupérer
     * automatiquement tous les aliments scannés dans cette liste.
     */
    public function products() { return $this->hasMany(Product::class); }
}

<?php

// Le namespace indique où se trouve le fichier dans ton projet (dossier app/Models)
namespace App\Models;

/**
 * Le Modèle Product utilise l'ORM "Eloquent".
 * Un ORM est un outil qui transforme les lignes de ta base de données (du texte/chiffres)
 * en Objets PHP faciles à manipuler. Au lieu de faire des requêtes SQL compliquées,
 * on manipule des objets qui "connaissent" leurs relations.
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// La classe Product hérite de "Model". Cela lui donne tous les pouvoirs de Laravel
// pour manipuler la table "products" dans la base de données.
class Product extends Model
{
    use HasFactory;

    /**
     * LA PROTECTION "$fillable" (Sécurité)
     * C'est une liste blanche. On dit à Laravel :
     * "J'autorise l'utilisateur à remplir ces colonnes précisément."
     * Cela empêche quelqu'un de malveillant de modifier d'autres données sensibles.
     */
    protected $fillable = [
        'barcode',      // Le code-barres du produit
        'name',         // Le nom (ex: Nutella)
        'brand',        // La marque (ex: Ferrero)
        'calories',     // L'énergie
        'nutriscore',   // La note A, B, C, D ou E
        'image_url',    // Le lien vers la photo
        'food_list_id'  // L'ID de la liste à laquelle appartient ce produit (La CLÉ ÉTRANGÈRE)
    ];

    /**
     * LA RELATION "belongsTo" (Appartient à)
     * C'est ici qu'on définit la logique métier :
     * "Un Produit appartient à une Liste d'aliments."
     * * Grâce à cette fonction, dans ton code, tu pourras faire : $product->foodList->name
     * pour obtenir instantanément le nom de la liste sans refaire de recherche.
     */
    public function foodList()
    {
        // On lie ce produit au modèle FoodList
        return $this->belongsTo(FoodList::class);
    }
}

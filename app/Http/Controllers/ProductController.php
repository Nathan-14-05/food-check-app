<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Pour appeler l'API

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user(); // Récupère l'utilisateur actuellement connecté
        $lists = $user->foodLists; // Récupère toutes ses listes via la relation hasMany

        // SECURITÉ : Si l'utilisateur n'a aucune liste (premier login), on lui en crée une d'office
        if ($lists->isEmpty()) {
            $list = $user->foodLists()->create(['name' => 'Ma Liste']);
            $lists = collect([$list]);
        }

        // GESTION DE LA LISTE ACTIVE :
        // On regarde si l'URL contient un ID (?list=2). Sinon, on prend la 1ère liste.
        $currentListId = $request->get('list', $lists->first()->id);
        $currentList = $user->foodLists()->findOrFail($currentListId);

        // MÉMOIRE DE SESSION :
        // On stocke l'ID de la liste affichée en session pour que le futur scan sache où s'enregistrer.
        session(['active_list_id' => $currentList->id]);

        $products = $currentList->products; // On ne récupère que les produits de la liste choisie

        // CALCUL DES STATISTIQUES (Logique métier)
        $totalProducts = $products->count();
        $avgCalories = $products->avg('calories') ?? 0;
        $healthyCount = $products->whereIn('nutriscore', ['A', 'B'])->count();

        // On prépare un tableau pour le graphique Chart.js
        $stats = [
            'Sain' => $healthyCount,
            'Modéré' => $products->where('nutriscore', 'C')->count(),
            'Mauvais' => $products->whereIn('nutriscore', ['D', 'E'])->count(),
        ];

        // On envoie toutes ces variables à la vue "products.index"
        return view('products.index', compact('products', 'totalProducts', 'avgCalories', 'healthyCount', 'stats', 'lists', 'currentList'));
    }

    public function create() {
        return view('products.create');
    }

    // --- FONCTION MAGIQUE ---
    public function getInfoFromApi($barcode) {
        // APPEL API : On contacte Open Food Facts avec le code-barres reçu
        $response = Http::withoutVerifying()->get("https://world.openfoodfacts.org/api/v0/product/{$barcode}.json");

        // Si l'API répond "OK" (status 1)
        if ($response->successful() && $response['status'] == 1) {
            $data = $response['product'];

            // On renvoie un objet JSON propre pour que le JavaScript du navigateur remplisse le formulaire
            return response()->json([
                'name' => $data['product_name'] ?? 'Inconnu',
                'brand' => $data['brands'] ?? 'Inconnue',
                'calories' => $data['nutriments']['energy-kcal_100g'] ?? 0,
                'nutriscore' => strtoupper($data['nutrition_grades'] ?? 'N'),
                // On récupère l'image principale (front)
                'image_url' => $data['image_front_url'] ?? null,
            ]);
        }

        return response()->json(['error' => 'Produit non trouvé'], 404);
    }

    public function store(Request $request)
    {
        // RÉCUPÉRATION DU CONTEXTE :
        // On récupère en session l'ID de la liste qui était ouverte sur le Dashboard
        $listId = session('active_list_id', auth()->user()->foodLists()->first()->id);
        $list = auth()->user()->foodLists()->findOrFail($listId);

        // CRÉATION EN BASE :
        // On utilise la relation list->products() pour créer l'aliment
        $list->products()->create([
            'name' => $request->name,
            'brand' => $request->brand,
            'calories' => $request->calories,
            'nutriscore' => $request->nutriscore,
            'barcode' => $request->barcode,
        ]);

        // REDIRECTION : On renvoie l'utilisateur sur la liste qu'il vient de compléter
        return redirect()->route('dashboard', ['list' => $listId])->with('success', 'Produit ajouté à ' . $list->name);
    }

    public function storeList(Request $request)
    {
        // VALIDATION : Sécurité de base.
        // On vérifie que le nom n'est pas vide, que c'est du texte et qu'il n'est pas trop long.
        $request->validate(['name' => 'required|string|max:255']);

        // ENREGISTREMENT LIÉ :
        // On dit : "Prends l'utilisateur connecté (auth()->user()),
        // va dans ses listes (foodLists()) et crée-en une nouvelle avec ce nom."
        // L'ID de l'utilisateur (user_id) est rempli automatiquement par Laravel grâce à la relation.
        auth()->user()->foodLists()->create([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Nouvelle liste créée !');
    }

    public function destroy($id) {
        $product = Product::findOrFail($id); // Trouve le produit ou affiche une erreur 404
        $product->delete(); // Supprime de la base de données

        return redirect('/aliments')->with('success', 'Produit supprimé !');
    }
}

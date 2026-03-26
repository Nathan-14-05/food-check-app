<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Pour appeler l'API

class ProductController extends Controller
{
    public function index() {
        $products = Product::all();

        // Stats de base
        $totalProducts = $products->count();
        $avgCalories = $products->avg('calories') ?? 0;

        // Stats simplifiées pour le graphique
        $stats = [
            'Sain' => $products->whereIn('nutriscore', ['A', 'B'])->count(),
            'Modéré' => $products->where('nutriscore', 'C')->count(),
            'Mauvais' => $products->whereIn('nutriscore', ['D', 'E'])->count(),
        ];

        // On garde healthyCount pour ta carte du haut
        $healthyCount = $stats['Sain'];

        return view('products.index', compact('products', 'totalProducts', 'avgCalories', 'healthyCount', 'stats'));
    }

    public function create() {
        return view('products.create');
    }

    // --- NOUVELLE FONCTION MAGIQUE ---
    public function getInfoFromApi($barcode) {
        // On appelle l'API (avec le correctif SSL d'avant)
        $response = Http::withoutVerifying()->get("https://world.openfoodfacts.org/api/v0/product/{$barcode}.json");

        if ($response->successful() && $response['status'] == 1) {
            $data = $response['product'];

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

    public function store(Request $request) {
        Product::create($request->all());
        return redirect('/aliments');
    }

    public function destroy($id) {
        $product = Product::findOrFail($id); // Trouve le produit ou affiche une erreur 404
        $product->delete(); // Supprime de la base de données

        return redirect('/aliments')->with('success', 'Produit supprimé !');
    }
}

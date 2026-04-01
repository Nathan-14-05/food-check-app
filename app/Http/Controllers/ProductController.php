<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Pour appeler l'API

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $lists = $user->foodLists;

        if ($lists->isEmpty()) {
            $list = $user->foodLists()->create(['name' => 'Ma Liste']);
            $lists = collect([$list]);
        }

        // On regarde si un ID de liste est passé dans l'URL (ex: /dashboard?list=2)
        // Sinon, on prend la première liste du compte
        $currentListId = $request->get('list', $lists->first()->id);
        $currentList = $user->foodLists()->findOrFail($currentListId);

        // On garde l'ID en session pour s'en souvenir lors du scan
        session(['active_list_id' => $currentList->id]);

        $products = $currentList->products;

        // Tes stats (inchangées)
        $totalProducts = $products->count();
        $avgCalories = $products->avg('calories') ?? 0;
        $healthyCount = $products->whereIn('nutriscore', ['A', 'B'])->count();
        $stats = [
            'Sain' => $healthyCount,
            'Modéré' => $products->where('nutriscore', 'C')->count(),
            'Mauvais' => $products->whereIn('nutriscore', ['D', 'E'])->count(),
        ];

        return view('products.index', compact('products', 'totalProducts', 'avgCalories', 'healthyCount', 'stats', 'lists', 'currentList'));
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

    public function store(Request $request)
    {
        // On récupère l'ID de la liste qu'on a stocké en session à l'étape 1
        // Si la session est vide (rare), on prend la première liste de l'user
        $listId = session('active_list_id', auth()->user()->foodLists()->first()->id);
        $list = auth()->user()->foodLists()->findOrFail($listId);

        $list->products()->create([
            'name' => $request->name,
            'brand' => $request->brand,
            'calories' => $request->calories,
            'nutriscore' => $request->nutriscore,
            'barcode' => $request->barcode,
        ]);

        return redirect()->route('dashboard', ['list' => $listId])->with('success', 'Produit ajouté à ' . $list->name);
    }

    public function storeList(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

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

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scanner un nouveau produit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <h1 class="text-2xl font-bold mb-6 text-gray-800">Ajouter un produit</h1>

                <div class="mb-8 p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <label class="block text-sm font-bold text-blue-800 mb-2">Scanner ou saisir le code-barres</label>
                    <div class="flex gap-2">
                        <input type="text" id="barcode_input" placeholder="Ex: 3017620422003"
                               class="flex-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <button type="button" onclick="searchProduct()"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">
                            Rechercher
                        </button>
                    </div>
                    <p id="status_msg" class="mt-2 text-sm text-gray-500"></p>
                </div>

                <form action="{{ route('products.store') }}" method="POST" id="product_form" class="hidden space-y-4">
                    @csrf
                    <input type="hidden" name="barcode" id="form_barcode">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nom du produit</label>
                            <input type="text" name="name" id="form_name" required readonly
                                   class="mt-1 block w-full bg-gray-50 border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Marque</label>
                            <input type="text" name="brand" id="form_brand" readonly
                                   class="mt-1 block w-full bg-gray-50 border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Calories (kcal/100g)</label>
                            <input type="number" name="calories" id="form_calories" readonly
                                   class="mt-1 block w-full bg-gray-50 border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nutri-Score</label>
                            <input type="text" name="nutriscore" id="form_nutriscore" readonly
                                   class="mt-1 block w-full bg-gray-50 border-gray-300 rounded-md shadow-sm font-bold text-center">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-xl font-black uppercase tracking-widest hover:bg-green-700 transition shadow-lg">
                            Confirmer et Ajouter au stock
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        async function searchProduct() {
            const barcode = document.getElementById('barcode_input').value;
            const status = document.getElementById('status_msg');
            const form = document.getElementById('product_form');

            if (!barcode) return;

            status.innerText = "Recherche en cours...";
            status.className = "mt-2 text-sm text-blue-600";

            try {
                const response = await fetch(`https://world.openfoodfacts.org/api/v0/product/${barcode}.json`);
                const data = await response.json();

                if (data.status === 1) {
                    const p = data.product;
                    document.getElementById('form_barcode').value = barcode;
                    document.getElementById('form_name').value = p.product_name || 'Inconnu';
                    document.getElementById('form_brand').value = p.brands || 'Inconnue';
                    document.getElementById('form_calories').value = p.nutriments['energy-kcal_100g'] || 0;
                    document.getElementById('form_nutriscore').value = (p.nutrition_grades || 'N/A').toUpperCase();

                    status.innerText = "Produit trouvé !";
                    status.className = "mt-2 text-sm text-green-600";
                    form.classList.remove('hidden');
                } else {
                    status.innerText = "Produit non trouvé sur Open Food Facts.";
                    status.className = "mt-2 text-sm text-red-600";
                    form.classList.add('hidden');
                }
            } catch (error) {
                status.innerText = "Erreur lors de la connexion à l'API.";
                status.className = "mt-2 text-sm text-red-600";
            }
        }
    </script>
</x-app-layout>

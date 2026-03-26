@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        <div class="bg-gray-50 p-6 border-b border-gray-100 text-center">
            <div id="image-preview-container" class="mb-4 hidden">
                <img id="image-preview" src="" class="w-24 h-24 mx-auto object-contain rounded-lg shadow-md bg-white p-1 border">
            </div>
            <h2 class="text-xl font-bold text-gray-800">Ajouter un aliment</h2>
            <p class="text-gray-400 text-sm">Scannez ou saisissez les infos</p>
        </div>

        <form action="/aliments" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="image_url" id="image_url_input">

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Code-barres</label>
                <div class="flex gap-2">
                    <input type="text" id="barcode" name="barcode" class="w-full border-gray-200 border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Ex: 3017620422003">
                    <button type="button" onclick="fetchProductData()" class="bg-gray-800 text-white px-4 rounded-lg hover:bg-black transition">🔍</button>
                </div>
                <p id="loader" class="text-blue-500 text-[10px] mt-1 hidden animate-pulse uppercase font-bold tracking-widest">Recherche en cours...</p>
            </div>

            <div class="grid grid-cols-1 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nom du produit</label>
                    <input type="text" id="name" name="name" required class="w-full border-gray-200 border rounded-lg p-2 outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Marque</label>
                    <input type="text" id="brand" name="brand" required class="w-full border-gray-200 border rounded-lg p-2 outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Calories</label>
                    <input type="number" id="calories" name="calories" required class="w-full border-gray-200 border rounded-lg p-2 outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nutriscore</label>
                    <select id="nutriscore" name="nutriscore" required class="w-full border-gray-200 border rounded-lg p-2 outline-none focus:border-blue-500">
                        <option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option><option value="E">E</option>
                    </select>
                </div>
            </div>

            <div class="pt-6 flex gap-4">
                <a href="/aliments" class="flex-1 text-center py-2 text-gray-400 hover:text-gray-600 text-sm font-semibold transition">Annuler</a>
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition">Enregistrer</button>
            </div>
        </form>
    </div>

    <script>
        function fetchProductData() {
            const barcode = document.getElementById('barcode').value;
            const loader = document.getElementById('loader');
            if (barcode.length < 5) return;
            loader.classList.remove('hidden');

            fetch(`/api/product/${barcode}`)
                .then(response => response.json())
                .then(data => {
                    loader.classList.add('hidden');
                    if (!data.error) {
                        document.getElementById('name').value = data.name;
                        document.getElementById('brand').value = data.brand;
                        document.getElementById('calories').value = Math.round(data.calories);
                        document.getElementById('nutriscore').value = data.nutriscore;

                        const previewContainer = document.getElementById('image-preview-container');
                        const previewImg = document.getElementById('image-preview');
                        const hiddenInput = document.getElementById('image_url_input');

                        if (data.image_url) {
                            previewImg.src = data.image_url;
                            hiddenInput.value = data.image_url;
                            previewContainer.classList.remove('hidden');
                        }
                    }
                });
        }
    </script>
@endsection

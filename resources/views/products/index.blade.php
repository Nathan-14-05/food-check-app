<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mon Tableau de Bord Nutritionnel') }}
            </h2>
            <a href="/aliments/nouveau" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-bold transition shadow-lg shadow-blue-100">
                + Scanner un produit
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">Mes Listes</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($lists as $list)
                                <a href="{{ route('dashboard', ['list' => $list->id]) }}"
                                   class="px-4 py-2 rounded-xl text-sm font-bold transition {{ $currentList->id == $list->id ? 'bg-blue-600 text-white shadow-md shadow-blue-100' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                    {{ $list->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <form action="{{ route('food-lists.store') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="name" placeholder="Nom de la nouvelle liste..." required
                               class="border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm w-full md:w-64">
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-black transition">
                            + Créer
                        </button>
                    </form>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-2xl">📦</div>
                    <div>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Total produits</p>
                        <p class="text-2xl font-black text-gray-800">{{ $totalProducts }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center text-2xl">🔥</div>
                    <div>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Moyenne Kcal</p>
                        <p class="text-2xl font-black text-gray-800">{{ round($avgCalories) }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-2xl">✅</div>
                    <div>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Produits Sains</p>
                        <p class="text-2xl font-black text-gray-800">{{ $healthyCount }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-gray-800 font-bold mb-6 text-center">Équilibre Alimentaire</h3>
                    <div class="relative flex justify-center">
                        <canvas id="nutriChart" width="200" height="200"></canvas>
                    </div>
                    <div class="mt-6 space-y-2">
                        <div class="flex justify-between text-xs font-bold">
                            <span class="text-green-600">Sain (A/B)</span>
                            <span>{{ $stats['Sain'] }}</span>
                        </div>
                        <div class="flex justify-between text-xs font-bold">
                            <span class="text-yellow-500">Modéré (C)</span>
                            <span>{{ $stats['Modéré'] }}</span>
                        </div>
                        <div class="flex justify-between text-xs font-bold">
                            <span class="text-red-500">À limiter (D/E)</span>
                            <span>{{ $stats['Mauvais'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-100">
                    <div class="p-6 border-b border-gray-50">
                        <h2 class="text-lg font-bold text-gray-800">Historique des scans ({{ $currentList->name }})</h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                            <tr class="bg-gray-50 text-gray-400 text-[10px] uppercase tracking-[0.2em]">
                                <th class="p-4">Produit</th>
                                <th class="p-4 text-center">Kcal</th>
                                <th class="p-4 text-center">Score</th>
                                <th class="p-4 text-right"></th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                            @forelse ($products as $product)
                                <tr class="group hover:bg-blue-50/30 transition">
                                    <td class="p-4">
                                        <div class="font-bold text-gray-700 leading-tight">{{ $product->name }}</div>
                                        <div class="text-[11px] text-gray-400 uppercase tracking-tighter">{{ $product->brand }}</div>
                                    </td>
                                    <td class="p-4 text-center text-sm font-medium text-gray-600">
                                        {{ $product->calories }}
                                    </td>
                                    <td class="p-4 text-center">
                                            <span class="w-7 h-7 inline-flex items-center justify-center rounded-lg font-black text-white text-xs
                                                {{ $product->nutriscore == 'A' ? 'bg-green-600' : '' }}
                                                {{ $product->nutriscore == 'B' ? 'bg-green-400' : '' }}
                                                {{ $product->nutriscore == 'C' ? 'bg-yellow-400' : '' }}
                                                {{ $product->nutriscore == 'D' ? 'bg-orange-500' : '' }}
                                                {{ $product->nutriscore == 'E' ? 'bg-red-600' : '' }}">
                                                {{ $product->nutriscore }}
                                            </span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <form action="/aliments/{{ $product->id }}" method="POST" onsubmit="return confirm('Supprimer ?');">
                                            @csrf @method('DELETE')
                                            <button class="opacity-0 group-hover:opacity-100 text-gray-300 hover:text-red-500 transition px-2">
                                                🗑️
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-10 text-center text-gray-400 italic">Aucun aliment enregistré dans cette liste.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('nutriChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Sain', 'Modéré', 'À limiter'],
                    datasets: [{
                        data: [{{ $stats['Sain'] }}, {{ $stats['Modéré'] }}, {{ $stats['Mauvais'] }}],
                        backgroundColor: ['#10b981', '#facc15', '#ef4444'],
                        borderWidth: 4,
                        borderColor: '#ffffff',
                        hoverOffset: 15
                    }]
                },
                options: {
                    cutout: '75%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    </script>
</x-app-layout>

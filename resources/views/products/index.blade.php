@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="max-w-6xl mx-auto px-4">

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
                <div class="relative">
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

            <div class="lg:col-span-2 bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-800">Historique des scans</h2>
                    <a href="/aliments/nouveau" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-bold transition shadow-lg shadow-blue-100">
                        + Scanner
                    </a>
                </div>

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
                            <td colspan="4" class="p-10 text-center text-gray-400 italic">Aucun aliment enregistré pour le moment.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
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
                    legend: { display: false } // On cache la légende car on a fait la nôtre en dessous
                }
            }
        });
    </script>
@endsection

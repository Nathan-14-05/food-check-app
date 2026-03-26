<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food-Check - Votre assistant nutrition</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-white shadow-md border-b border-gray-200">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="/aliments" class="text-2xl font-bold text-blue-600 flex items-center gap-2">
                    <span>🍎</span> Food-Check
                </a>
                <div class="ml-10 flex space-x-8">
                    <a href="/aliments" class="text-gray-600 hover:text-blue-600 font-medium transition">Ma Liste</a>
                    <a href="/aliments/nouveau" class="text-gray-600 hover:text-blue-600 font-medium transition">Scanner un produit</a>
                </div>
            </div>
            <div class="flex items-center">
                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-bold uppercase tracking-wider text-[10px]">Version Alpha v1.0</span>
            </div>
        </div>
    </div>
</nav>

<main class="py-10">
    @yield('content')
</main>

<footer class="text-center py-6 text-gray-400 text-sm">
    &copy; 2026 Food-Check App - Connecté à Open Food Facts
</footer>

</body>
</html>

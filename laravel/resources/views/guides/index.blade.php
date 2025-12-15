<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Handleidingen</title>
    @vite('resources/css/app.css')
    <script src="https://kit.fontawesome.com/21e98e6012.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-50 text-gray-900">
    @include('header')

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
        <div>
            <p class="text-sm font-semibold text-emerald-700 uppercase tracking-wide">Handleidingen</p>
            <h1 class="mt-1 text-3xl font-bold text-gray-900">Kies een gids</h1>
            <p class="mt-2 text-gray-600">Stap-voor-stap uitleg voor monteurs en beheerders.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('guides.monteur') }}"
                class="group bg-white rounded-2xl border border-gray-200/70 shadow-sm p-6 hover:shadow-md transition">
                <div class="flex items-start gap-4">
                    <div
                        class="h-11 w-11 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center ring-1 ring-sky-100">
                        <i class="fa-solid fa-clipboard-list"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-semibold text-gray-900 group-hover:text-sky-800">Monteur / gebruiker</h2>
                        <p class="mt-1 text-gray-600 text-sm">Rapportage invullen, voortgang bewaren en versturen.</p>
                        <span class="mt-3 inline-flex items-center gap-1 text-sky-700 text-sm font-medium">
                            Open gids <i class="fa-solid fa-arrow-right text-xs"></i>
                        </span>
                    </div>
                </div>
            </a>

            <a href="{{ route('guides.beheerder') }}"
                class="group bg-white rounded-2xl border border-gray-200/70 shadow-sm p-6 hover:shadow-md transition">
                <div class="flex items-start gap-4">
                    <div
                        class="h-11 w-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center ring-1 ring-amber-100">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-semibold text-gray-900 group-hover:text-amber-800">Beheerder</h2>
                        <p class="mt-1 text-gray-600 text-sm">Rapporten beheren en inspectielijsten aanmaken.</p>
                        <span class="mt-3 inline-flex items-center gap-1 text-amber-700 text-sm font-medium">
                            Open gids <i class="fa-solid fa-arrow-right text-xs"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </main>
</body>

</html>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beheer menu</title>
    @vite('resources/css/app.css')
    <script src="https://kit.fontawesome.com/a2e0a4c3c1.js" crossorigin="anonymous"></script>
</head>

<body class="bg-slate-50 text-gray-900">
    @include('header')

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
        <header class="text-center space-y-2">
            <p class="text-sm uppercase tracking-wide text-slate-500">Beheer</p>
            <h1 class="text-3xl font-semibold">Waar wil je heen?</h1>
            <p class="text-gray-600">Kies een onderdeel om rapportages of inspectielijsten te beheren.</p>
        </header>

        <section class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <a href="{{ route('inspections.beheer') }}"
                class="group rounded-2xl bg-white shadow hover:shadow-lg transition flex flex-col items-center text-center px-6 py-10 border border-transparent hover:border-sky-100">
                <div class="h-14 w-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4 transition group-hover:scale-105">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
                <h2 class="text-xl font-semibold">Inspectie beheer</h2>
                <p class="text-gray-600 mt-2">Overzicht van alle inspectielijsten en hun categorieen.</p>
                <span class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-emerald-700 group-hover:gap-3 transition">
                    Openen <i class="fa-solid fa-arrow-right"></i>
                </span>
            </a>

            <a href="{{ route('reports.beheer') }}"
                class="group rounded-2xl bg-white shadow hover:shadow-lg transition flex flex-col items-center text-center px-6 py-10 border border-transparent hover:border-sky-100">
                <div class="h-14 w-14 rounded-full bg-sky-50 text-sky-600 flex items-center justify-center text-2xl mb-4 transition group-hover:scale-105">
                    <i class="fa-solid fa-table-list"></i>
                </div>
                <h2 class="text-xl font-semibold">Rapportage beheer</h2>
                <p class="text-gray-600 mt-2">Beheer concepten, verzonden rapportages en archief.</p>
                <span class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-sky-700 group-hover:gap-3 transition">
                    Openen <i class="fa-solid fa-arrow-right"></i>
                </span>
            </a>
        </section>
    </main>
</body>

</html>

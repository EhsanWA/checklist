<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Manager</title>
    @vite('resources/css/app.css')
    {{-- Font Awesome voor icoontjes --}}
    <script src="https://kit.fontawesome.com/21e98e6012.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-50 text-gray-900">

    @include('header')

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

        {{-- Landingspagina: Hero intro + twee kaarten (Rapportage invullen, Beheer). Kaarten volledig klikbaar met focus ring. --}}
        <section class="text-center">
            <h1 class="text-4xl sm:text-5xl font-bold tracking-tight text-gray-900">
                Welkom bij de Report Manager
            </h1>
            <p class="mt-3 text-lg text-gray-500">
                Kies een optie. <span class="font-medium text-gray-700">Rapportage invullen</span> is voor iedereen.
                <span class="font-medium text-gray-700">Beheer</span> is afgeschermd met een beheercode (PIN).
            </p>
        </section>

        {{-- Hoofdkaarten: Rapportage invullen + Beheer (dynamische link/status op basis van admin sessie). --}}
        <section class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-10 max-w-5xl mx-auto">

            {{-- Kaart 1: Rapportage invullen (publiek beschikbaar). --}}
            <a href="{{ route('reports.index') }}" class="group block focus:outline-none focus:ring-2 focus:ring-sky-300 rounded-3xl">
                <article
                    class="bg-white rounded-3xl shadow-sm border border-gray-200/70 p-6 sm:p-8 hover:shadow-md transition">
                    <div class="flex items-start gap-4">
                        <div class="shrink-0">
                            <div
                                class="h-12 w-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center ring-1 ring-sky-100">
                                <i class="fa-solid fa-clipboard text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-semibold text-gray-900">Rapportage invullen</h2>
                            <p class="mt-1.5 text-gray-500">
                                Start direct met het invullen van een nieuwe rapportage of melding.
                            </p>

                            <div class="mt-5 inline-flex items-center gap-2 font-medium text-sky-700 group-hover:text-sky-900">
                                Ga naar formulier
                                <i class="fa-solid fa-arrow-up-right-from-square text-sm"></i>
                            </div>
                        </div>
                    </div>
                </article>
            </a>

            {{-- Kaart 2: Beheer (beheercode login vereist, routes dynamisch). --}}
            @php
            $beheerUrl = session('is_admin') === true ? route('reports.beheer') : route('admin.login');
            $beheerLabel = session('is_admin') === true ? 'Naar beheer' : 'Naar beheer (PIN)';
            $beheerIcon = session('is_admin') === true ? 'fa-chevron-right' : 'fa-lock';
            @endphp

            <a href="{{ $beheerUrl }}" class="group block focus:outline-none focus:ring-2 focus:ring-sky-300 rounded-3xl">
                <article
                    class="bg-white rounded-3xl shadow-sm border border-gray-200/70 p-6 sm:p-8 hover:shadow-md transition">
                    <div class="flex items-start gap-4">
                        <div class="shrink-0">
                            <div
                                class="h-12 w-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center ring-1 ring-amber-100">
                                <i class="fa-solid fa-shield-halved text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-semibold text-gray-900">Beheerder</h2>
                            <p class="mt-1.5 text-gray-500">
                                Log in om rapportages te beheren (aanmaken, bewerken, verwijderen).
                            </p>

                            <div class="mt-5 inline-flex items-center gap-2 font-medium text-sky-700 group-hover:text-sky-900">
                                {{ $beheerLabel }}
                                <i class="fa-solid {{ $beheerIcon }} text-sm"></i>
                            </div>
                        </div>
                    </div>
                </article>
            </a>

        </section>

        {{-- Snelkoppelingen / Documentatie / Overig --}}
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold">Snelkoppelingen</h3>
                {{-- optioneel badge of versie --}}
                {{-- <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600 ring-1 ring-gray-200">v1.0</span> --}}
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Handleidingen --}}
                <a href="{{ route('guides.index') }}"
                    class="group bg-white rounded-2xl shadow-sm border border-gray-200/70 p-6 hover:shadow-md transition">
                    <div class="flex items-start gap-4">
                        <div
                            class="h-11 w-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center ring-1 ring-emerald-100">
                            <i class="fa-solid fa-chalkboard-user"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-900 group-hover:text-emerald-700">Handleidingen
                            </h4>
                            <p class="mt-1 text-gray-500 text-sm">Stap-voor-stap handleiding voor monteurs.</p>
                            <span class="mt-3 inline-flex items-center gap-1 text-emerald-700 text-sm font-medium">
                                Bekijk handleidingen <i class="fa-solid fa-arrow-right text-xs"></i>
                            </span>
                        </div>
                    </div>
                </a>

            </div>
        </section>

        {{-- Extra witruimte --}}
        <div class="h-6"></div>
    </main>
</body>

</html>

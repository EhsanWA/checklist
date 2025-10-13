<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>home</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gradient-to-b from-slate-50 to-white dark:from-slate-900 dark:to-slate-950">
    {{-- Header --}}
    @include('header')

    <main>
        <section class="max-w-5xl mx-auto px-4 sm:px-6 py-10 sm:py-12">
            <header class="mb-8 sm:mb-10">
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100">
                    Welkom bij de Report Manager
                </h1>
                <p class="mt-2 text-slate-600 dark:text-slate-300">
                    Kies een optie. <span class="font-medium">Rapportage invullen</span> is voor iedereen.
                    <span class="font-medium">Beheer</span> is alleen voor geautoriseerde beheerders.
                </p>
            </header>

            {{-- Twee hoofdkaarten: mobiel = 1 kolom, tablet/desktop = 2 kolommen --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Rapportage invullen --}}
                {{-- TODO: vervang href="#" later met jouw route/url naar het formulier --}}
                <a href="{{ route('reports.index') }}" role="button" aria-label="Open het rapportageformulier"
                    class="group block rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/70 backdrop-blur p-6 sm:p-8 shadow-sm hover:shadow-md transition-all focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <div class="flex items-start gap-4">
                        <span
                            class="inline-flex h-12 w-12 items-center justify-center rounded-xl ring-1 ring-inset ring-slate-200 dark:ring-slate-700 bg-white dark:bg-slate-800 group-hover:scale-105 transition">
                            {{-- icoon: formulier --}}
                            <svg viewBox="0 0 24 24" class="h-6 w-6 text-sky-600 dark:text-sky-400" fill="currentColor"
                                aria-hidden="true">
                                <path
                                    d="M9 2a1 1 0 0 0-1 1v1H6.5A2.5 2.5 0 0 0 4 6v12a2.5 2.5 0 0 0 2.5 2.5h11A2.5 2.5 0 0 0 20 18V6a2.5 2.5 0 0 0-2.5-2.5H16V3a1 1 0 0 0-1-1H9Zm1 2h4v1h-4V4Z" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-semibold text-slate-900 dark:text-slate-100">
                                Rapportage invullen
                            </h2>
                            <p class="mt-1 text-slate-600 dark:text-slate-300">
                                Start direct met het invullen van een nieuwe rapportage of melding.
                            </p>
                            <div
                                class="mt-5 inline-flex items-center gap-2 text-sm font-medium text-sky-700 dark:text-sky-300">
                                Ga naar formulier
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M10.293 3.293a1 1 0 011.414 0l5 5a1 1 0 01-1.414 1.414L12 6.414V16a1 1 0 11-2 0V6.414L5.707 9.707A1 1 0 114.293 8.293l5-5z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                {{-- Beheerder (login) --}}
                {{-- TODO: vervang href="#" later met jouw route/url naar de beheer-login --}}
                <a href="{{ route('reports.beheer') }}" role="button" aria-label="Ga naar beheer login"
                    class="group block rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/70 backdrop-blur p-6 sm:p-8 shadow-sm hover:shadow-md transition-all focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <div class="flex items-start gap-4">
                        <span
                            class="inline-flex h-12 w-12 items-center justify-center rounded-xl ring-1 ring-inset ring-slate-200 dark:ring-slate-700 bg-white dark:bg-slate-800 group-hover:scale-105 transition">
                            {{-- icoon: schild/slot --}}
                            <svg viewBox="0 0 24 24" class="h-6 w-6 text-amber-600 dark:text-amber-400"
                                fill="currentColor" aria-hidden="true">
                                <path
                                    d="M12 2 4 5v6c0 5.55 3.84 10.74 8 12 4.16-1.26 8-6.45 8-12V5l-8-3Zm0 19c-2.61-.93-6-5.06-6-10V6.3l6-2.25 6 2.25V11c0 4.94-3.39 9.07-6 10Z" />
                                <path
                                    d="M12 8a3 3 0 0 0-3 3v1a1 1 0 0 0-1 1v3h8v-3a1 1 0 0 0-1-1v-1a3 3 0 0 0-3-3Zm-1 4v-1a1 1 0 1 1 2 0v1h-2Z" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-semibold text-slate-900 dark:text-slate-100">
                                Beheerder
                            </h2>
                            <p class="mt-1 text-slate-600 dark:text-slate-300">
                                Log in om rapportages te beheren (aanmaken, bewerken, verwijderen).
                            </p>
                            <div
                                class="mt-5 inline-flex items-center gap-2 text-sm font-medium text-amber-700 dark:text-amber-300">
                                Naar beheer
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </section>
    </main>
</body>

</html>

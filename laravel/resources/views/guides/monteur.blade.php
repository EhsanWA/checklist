<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Handleiding Monteur</title>
    @vite('resources/css/app.css')
    <script src="https://kit.fontawesome.com/21e98e6012.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-50 text-gray-900">
    @include('header')

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
        <div class="space-y-2">
            <p class="text-sm font-semibold text-sky-700 uppercase tracking-wide">Handleiding</p>
            <h1 class="text-3xl font-bold text-gray-900">Monteur / gebruiker</h1>
            <p class="text-gray-600">Kort stappenplan om rapportages in te vullen en te versturen.</p>
        </div>

        <ol class="space-y-4 text-gray-800">
            <li class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
                <p class="font-semibold text-lg text-gray-900">1. Open het rapport</p>
                <p class="mt-1 text-gray-600 text-sm">Ga via <span class="font-medium">Rapportage invullen</span> naar een bestaand rapport of start een nieuwe.</p>
            </li>
            <li class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
                <p class="font-semibold text-lg text-gray-900">2. Werk de checklist af</p>
                <p class="mt-1 text-gray-600 text-sm">Klik een check en kies <span class="font-medium">Gecontroleerd</span> of <span class="font-medium">Bijzonderheden</span>. Voeg bij bijzonderheden notities en foto’s toe.</p>
                <p class="mt-1 text-gray-600 text-sm">Gebruik het zoekveld om snel op label of code te filteren.</p>
            </li>
            <li class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
                <p class="font-semibold text-lg text-gray-900">3. Voortgang opslaan</p>
                <p class="mt-1 text-gray-600 text-sm">Klik <span class="font-medium">Opslaan</span> om tussentijds op te slaan. Openstaande (pending) checks blijven bewaard.</p>
            </li>
            <li class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
                <p class="font-semibold text-lg text-gray-900">4. Handtekening en verzenden</p>
                <p class="mt-1 text-gray-600 text-sm">Zorg dat alle checks geen <span class="font-medium">pending</span> meer hebben. Open <span class="font-medium">Handtekening & verzenden</span>, teken en klik <span class="font-medium">Verstuur rapportage</span>. De PDF wordt automatisch opgeslagen en gemaild.</p>
            </li>
            <li class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
                <p class="font-semibold text-lg text-gray-900">5. Verzonden PDF’s terugzien</p>
                <p class="mt-1 text-gray-600 text-sm">In het rapport vind je onder <span class="font-medium">Verzonden PDF’s</span> alle eerder verstuurde bestanden.</p>
            </li>
        </ol>
    </main>
</body>

</html>

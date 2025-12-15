<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Handleiding Beheerder</title>
    @vite('resources/css/app.css')
    <script src="https://kit.fontawesome.com/21e98e6012.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-50 text-gray-900">
    @include('header')

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
        <div class="space-y-2">
            <p class="text-sm font-semibold text-amber-700 uppercase tracking-wide">Handleiding</p>
            <h1 class="text-3xl font-bold text-gray-900">Beheerder</h1>
            <p class="text-gray-600">Basisstappen voor rapportbeheer en inspectielijsten.</p>
        </div>

        <ol class="space-y-4 text-gray-800">
            <li class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
                <p class="font-semibold text-lg text-gray-900">1. Inloggen op beheer</p>
                <p class="mt-1 text-gray-600 text-sm">Ga via <span class="font-medium">Beheerder</span> naar het PIN-scherm en log in. Je komt op het beheermenu.</p>
            </li>
            <li class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
                <p class="font-semibold text-lg text-gray-900">2. Rapporten beheren</p>
                <p class="mt-1 text-gray-600 text-sm">Open <span class="font-medium">Rapportages beheren</span> voor lijsten per status. Je kunt rapporten aanmaken, bewerken (velden/status) of verwijderen.</p>
            </li>
            <li class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
                <p class="font-semibold text-lg text-gray-900">3. Inspectielijsten maken/bijwerken</p>
                <p class="mt-1 text-gray-600 text-sm">Ga naar <span class="font-medium">Inspectielijsten</span>. Maak een lijst met titel/omschrijving, voeg categorieën toe met checks (label, code optioneel, required, severity). Bewerken vervangt de bestaande structuur.</p>
            </li>
            <li class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
                <p class="font-semibold text-lg text-gray-900">4. Koppelen aan rapporten</p>
                <p class="mt-1 text-gray-600 text-sm">Bij het aanmaken of bewerken van een rapport kies je de inspectielijst. De checks verschijnen direct voor de gebruiker.</p>
            </li>
            <li class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
                <p class="font-semibold text-lg text-gray-900">5. Bestanden en status</p>
                <p class="mt-1 text-gray-600 text-sm">Handtekeningen en foto’s staan in de publieke storage; terugzetten naar <span class="font-medium">gecontroleerd</span> verwijdert oude foto’s van die check. Verzonden PDF’s worden opgeslagen en zijn zichtbaar bij het rapport.</p>
            </li>
        </ol>
    </main>
</body>

</html>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meetrapport MRP2920</title>
    @vite('resources/css/app.css')
    <script src="https://kit.fontawesome.com/21e98e6012.js" crossorigin="anonymous"></script>
</head>
<body class="bg-white text-gray-800">
    <!-- Header -->
    <header class="bg-sky-500 flex items-center p-0 drop-shadow-md">
        <div class="">
            <img src="{{ asset('images/logo_marine.png') }}" alt="Koninklijke Marine Logo" class="h-20">
        </div>
        <i class="ml-auto mr-6 fa-solid fa-bars text-3xl" style="color: #ffffff;"></i>
    </header>

    <!-- Tabs -->
    <nav class="flex border-b drop-shadow-md">
        <a href="#" class="flex-1 text-center py-3 font-semibold border-b-2 border-sky-500 text-sky-600">Gecontroleerd</a>
        <a href="#" class="flex-1 text-center py-3 font-semibold hover:text-sky-600">Lijst</a>
        <a href="#" class="flex-1 text-center py-3 font-semibold hover:text-sky-600">Bijzonderheden</a>
    </nav>

    <main class="p-4">
        <!-- Placeholder for dynamic content -->
        <p class="text-gray-500">Geen gegevens beschikbaar...</p>
    </main>
</body>
</html>

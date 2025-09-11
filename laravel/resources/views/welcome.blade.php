{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koninklijke Marine</title>
    @vite('resources/css/app.css') {{-- Tailwind via Vite --}}
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

    {{-- Top navigation bar --}}
    <header class="bg-sky-500 flex items-center p-0 drop-shadow-md">
        <div class="">
            <img src="{{ asset('images/logo_marine.png') }}" alt="Koninklijke Marine Logo" class="h-20">
        </div>
    </header>

    {{-- Welcome section --}}
    <main class="flex-grow flex flex-col items-center justify-center bg-blue-100">
        <div class="bg-white shadow-md rounded-lg p-10 text-center w-full max-w-3xl">
            <h2 class="text-2xl font-bold mb-2">Welkom</h2>
            <p class="text-gray-600 mb-8">kies hieronder welke rapport je wilt invullen.</p>

            {{-- Grid of buttons --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <button class="bg-gray-200 hover:bg-gray-300 rounded-lg py-6 text-gray-700 font-medium">Rapport 1</button>
                <button class="bg-gray-200 hover:bg-gray-300 rounded-lg py-6 text-gray-700 font-medium">Rapport 2</button>
                <button class="bg-gray-200 hover:bg-gray-300 rounded-lg py-6 text-gray-700 font-medium">Rapport 3</button>
                <button class="bg-gray-200 hover:bg-gray-300 rounded-lg py-6 text-gray-700 font-medium">Rapport 4</button>
                <button class="bg-gray-200 hover:bg-gray-300 rounded-lg py-6 text-gray-700 font-medium">Rapport 5</button>
                <button class="bg-gray-200 hover:bg-gray-300 rounded-lg py-6 text-gray-700 font-medium">Rapport 6</button>
            </div>
        </div>
    </main>

</body>
</html>

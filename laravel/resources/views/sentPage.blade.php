<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meetrapport MRP2920</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/window.js'])
    <script src="https://kit.fontawesome.com/21e98e6012.js" crossorigin="anonymous"></script>
</head>

<body class="bg-white text-gray-800">
    <!-- Header -->
    @include('header')
    <div class="flex justify-center items-center flex-col mt-20 gap-6">
        <h2 class="text-xl font-bold">Rapport verzonden!</h2>
        <a href="{{ route('home') }}" class="bg-blue-500 hover:bg-blue-600 text-white rounded-sm px-4 py-3">terug naar beginscherm</a>
    </div>
</body>

</html>
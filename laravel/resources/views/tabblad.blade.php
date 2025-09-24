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
    @include('components.header')

    <!-- Tabs -->
    @include('components.tabNav')

    <main class="p-4">
        <!-- Placeholder for dynamic content -->
        <p class="text-gray-500">Geen gegevens beschikbaar...</p>
    </main>
    @include('components.sidebar')
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('translate-x-full');
            sidebar.classList.toggle('translate-x-0');
        }
    </script>
</body>
</html>

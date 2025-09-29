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
    @include('header')

    <!-- Tabs -->
    @include('tabNav')

    <main class="p-4">
        <div id="tab1-content" class="tab-content">Content voor Gecontroleerd</div>
        <div id="tab2-content" class="tab-content hidden">Content voor Bijzonderheden</div>
        <div id="tab3-content" class="tab-content hidden">Content voor Opdrachten</div> 
    </main>
    @include('sidebar')
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('translate-x-full');
            sidebar.classList.toggle('translate-x-0');
        }

        function switchTab(tabIndex) {
            // verberg alle tab-inhoud
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            // Verwijder actieve styling van alle knoppen
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('bg-sky-500', 'text-white');
                el.classList.add('bg-gray-200', 'text-gray-800');
            });
            // Toon geselecteerde tab-inhoud
            document.getElementById(`tab${tabIndex}-content`).classList.remove('hidden');
            // Voeg actieve styling toe aan geselecteerde knop
            document.getElementById(`tab${tabIndex}-btn`).classList.add('bg-sky-500', 'text-white');
            document.getElementById(`tab${tabIndex}-btn`).classList.remove('bg-gray-200', 'text-gray-800');
        }
        // Standaard naar de eerste tab
        switchTab(1);
    </script>
</body>

</html>
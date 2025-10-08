<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meetrapport MRP2920</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/21e98e6012.js" crossorigin="anonymous"></script>
</head>
<body class="bg-white text-gray-800">
    <!-- Header -->
    @include('header')

    <!-- Tabs -->
    @include('tabNav')

    <main class="p-4">
        <div id="tab1-content" class="tab-content hidden"></div>
        
        <div id="tab2-content" class="tab-content relative z-1">
            @foreach($reports as $report)
            @include('reportCard', ['report' => $report])
            @endforeach
        </div>
        <div id="tab3-content" class="tab-content hidden"></div>
    </main>
    @include('sidebar')

</body>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("translate-x-full");
        sidebar.classList.toggle("translate-x-0");
    }

    function switchTab(tabIndex) {
        // verberg alle tab contents
        document
            .querySelectorAll(".tab-content")
            .forEach((el) => el.classList.add("hidden"));

        // reset all tab buttons
        document.querySelectorAll(".tab-btn").forEach((el) => {
            el.classList.remove("bg-sky-500", "text-white");
            el.classList.add("bg-gray-200", "text-gray-800");
        });

        // laat de geselecteerde tab zien
        document
            .getElementById(`tab${tabIndex}-content`)
            .classList.remove("hidden");
        const activeBtn = document.getElementById(`tab${tabIndex}-btn`);
        activeBtn.classList.add("bg-sky-500", "text-white");
        activeBtn.classList.remove("bg-gray-200", "text-gray-800");
    }

    // standaard tab
    switchTab(2);
</script>

</html>

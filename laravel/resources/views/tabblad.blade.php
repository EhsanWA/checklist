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
        <div id="tab1-content" class="tab-content relative z-1">
            @foreach($reports as $report)
            @include('reportCard', ['report' => $report])
            @endforeach
        </div>

        <div id="tab2-content" class="tab-content hidden"></div>
        <div id="tab3-content" class="tab-content hidden"></div>
    </main>

    <!-- Sidebar -->
    @include('sidebar')


</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        function getTabContent(index) {
            return document.getElementById(`tab${index}-content`);
        }

        // Make reports draggable
        document.querySelectorAll('.draggable-report').forEach(function(el) {
            el.addEventListener('dragstart', function(ev) {
                const id = ev.currentTarget.id || (ev.currentTarget.id = `report-${Math.random().toString(36).slice(2)}`);
                ev.dataTransfer.setData('text/plain', id);
                ev.dataTransfer.effectAllowed = 'move';
                ev.currentTarget.classList.add('dragging');
            });

            el.addEventListener('dragend', function(ev) {
                ev.currentTarget.classList.remove('dragging');
            });
        });

        // Allow dropping into tab contents
        document.querySelectorAll('.tab-content').forEach(function(tab) {
            tab.addEventListener('dragover', function(ev) {
                ev.preventDefault();
                ev.dataTransfer.dropEffect = 'move';
                tab.classList.add('drag-over');
            });

            tab.addEventListener('dragleave', function() {
                tab.classList.remove('drag-over');
            });

            tab.addEventListener('drop', function(ev) {
                ev.preventDefault();
                tab.classList.remove('drag-over');
                const reportId = ev.dataTransfer.getData('text/plain');
                const draggedEl = document.getElementById(reportId);
                if (draggedEl) tab.appendChild(draggedEl);
            });
        });

        // Enable dropping via tab buttons
        document.querySelectorAll('.tab-btn').forEach(function(btn) {
            btn.addEventListener('dragover', function(ev) {
                ev.preventDefault();
                this.classList.add('drag-over-tab-btn');
                const idx = this.id.match(/\d+/)?.[0];
                if (idx) switchTab(Number(idx));
            });

            btn.addEventListener('dragleave', function() {
                this.classList.remove('drag-over-tab-btn');
            });

            btn.addEventListener('drop', function(ev) {
                ev.preventDefault();
                this.classList.remove('drag-over-tab-btn');
                const reportId = ev.dataTransfer.getData('text/plain');
                const draggedEl = document.getElementById(reportId);
                const idx = this.id.match(/\d+/)?.[0];
                if (idx && draggedEl) {
                    switchTab(Number(idx));
                    const target = getTabContent(idx);
                    target.appendChild(draggedEl);
                }
            });
        });

    });


    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('translate-x-full');
        sidebar.classList.toggle('translate-x-0');
    }

    function switchTab(tabIndex) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));

        // Reset all tab buttons
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('bg-sky-500', 'text-white');
            el.classList.add('bg-gray-200', 'text-gray-800');
        });

        // Show selected tab
        document.getElementById(`tab${tabIndex}-content`).classList.remove('hidden');
        const activeBtn = document.getElementById(`tab${tabIndex}-btn`);
        activeBtn.classList.add('bg-sky-500', 'text-white');
        activeBtn.classList.remove('bg-gray-200', 'text-gray-800');
    }

    // Default tab
    switchTab(1);
</script>

</html>
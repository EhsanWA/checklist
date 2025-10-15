<div id="report-{{ $report->id ?? uniqid() }}" 
     class="draggable-report max-w-3xl mx-auto bg-white rounded-lg shadow p-6 mt-10" 
     draggable="true">
     
    <h1 class="text-2xl font-bold">{{ $report->title ?? 'Untitled Report' }}</h1>

    <p class="text-sm text-gray-500 mt-1">
        Aangemaakt: {{ $report->created_at ?? 'Onbekend' }} • Status: {{ $report->status ?? 'N/A' }}
    </p>

    <div class="flex gap-2 mt-4"></div>
</div>

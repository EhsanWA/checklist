{{-- resources/views/reports/index.blade.php (binnen de foreach) --}}
<div class="bg-gray-200 rounded-lg p-4 text-gray-800">
    <a href="{{ route('reports.show', $report) }}" class="block font-medium">
        {{ $report->title }}
        <div class="text-xs text-gray-500 mt-1">
            {{ $report->created_at->format('d-m-Y H:i') }}
            @if ($report->status !== 'draft')
                • {{ ucfirst($report->status) }}
            @endif
        </div>
    </a>

    <div class="flex gap-2 mt-3">
        <a href="{{ route('reports.edit', $report) }}"
            class="px-3 py-1 rounded bg-blue-600 hover:bg-blue-700 text-white text-sm">Bewerken</a>

        <form method="POST" action="{{ route('reports.destroy', $report) }}"
            onsubmit="return confirm('Weet je zeker dat je ‘{{ $report->title }}’ wilt verwijderen?');">
            @csrf @method('DELETE')
            <button class="px-3 py-1 rounded bg-red-600 hover:bg-red-700 text-white text-sm">
                Verwijderen
            </button>
        </form>
    </div>
</div>

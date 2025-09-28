<div id="sidebar"
    class="fixed top-20 right-0 w-72 h-full bg-sky-500 shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50 overflow-y-auto">

    <div class="p-4 text-white font-semibold">
        Kies hier een ander rapport <br>
        of ga <a href="{{ route('reports.index') }}" class="underline">terug naar beginscherm</a>
    </div>

    <div class="space-y-2 p-4">
        @forelse($reports as $r)
            <a href="{{ route('reports.show', $r) }}" @class([
                'block rounded-lg px-3 py-2 shadow transition',
                'bg-white text-gray-800 hover:bg-gray-100' => $r->id !== $report->id,
                'bg-sky-100 text-sky-900 ring-2 ring-white/50' => $r->id === $report->id, // highlight huidig
            ])>
                <div class="font-medium truncate">
                    {{ $r->title ?: 'Naamloos rapport' }}
                </div>
                <div class="text-xs text-gray-500">
                    {{ $r->created_at->format('d-m-Y H:i') }}
                    @if (!empty($r->status))
                        • {{ ucfirst($r->status) }}
                    @endif
                </div>
            </a>
        @empty
            <p class="text-white text-sm">Geen rapportages beschikbaar.</p>
        @endforelse
    </div>
</div>

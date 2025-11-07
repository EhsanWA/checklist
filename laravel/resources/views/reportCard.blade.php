{{-- In resources/views/reports/show.blade.php --}}
@if ($report->inspectionList)
    <div class="mt-8 rounded-xl border bg-white p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">Inspectielijst</h2>
            <a href="{{ route('inspections.show', $report->inspectionList) }}" class="text-sky-600 hover:underline">Open
                volledige inspectie »</a>
        </div>

        {{-- Voorbeeld weergave – pas aan jouw model/relaties (categories/checks) --}}
        <p class="text-gray-700"><strong>Titel:</strong> {{ $report->inspectionList->title }}</p>
        <p class="text-gray-700"><strong>Aangemaakt op:</strong>
            {{ $report->inspectionList->created_at->format('d-m-Y H:i') }}</p>

        {{-- Als je categories/checks hebt: --}}
        @if ($report->inspectionList->relationLoaded('categories') || method_exists($report->inspectionList, 'categories'))
            <div class="mt-4 space-y-4">
                @foreach ($report->inspectionList->categories as $cat)
                    <div class="border rounded-lg p-4">
                        <h3 class="font-medium">{{ $cat->name }}</h3>
                        <ul class="list-disc ml-6 mt-2">
                            @foreach ($cat->checks as $check)
                                <li>{{ $check->label }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@else
    <div class="mt-8 rounded-xl border bg-yellow-50 text-yellow-800 p-4">
        Geen inspectielijst gekoppeld.
    </div>
@endif

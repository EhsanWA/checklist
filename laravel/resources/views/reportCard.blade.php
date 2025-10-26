@if ($inspection)
<div class="space-y-6">
    <h2 class="text-xl font-semibold">{{ $inspection->title }}</h2>
    @if ($inspection->description)
    <p class="text-gray-600">{{ $inspection->description }}</p>
    @endif

    @foreach ($inspection->categories as $cat)
    <section class="draggable-report rounded-2xl border border-gray-200 bg-white p-4 space-y-3">
        <h3 class="font-semibold">{{ $cat->name }}</h3>

        <div class="grid gap-3">
            @foreach ($cat->checks as $chk)
            <div class="rounded-xl border px-3 py-2 bg-gray-50">
                <div class="flex items-start justify-between">
                    <p class="font-medium">{{ $chk->label }}</p>
                    @if ($chk->code)
                    <span class="text-xs text-gray-500 ml-3">{{ $chk->code }}</span>
                    @endif
                </div>
                <div class="mt-2 text-xs">
                    <span class="inline-block px-2 py-0.5 rounded bg-gray-200">
                        {{ $chk->required ? 'Verplicht' : 'Optioneel' }}
                    </span>
                    <span class="inline-block ml-2 px-2 py-0.5 rounded bg-gray-200">
                        {{ ucfirst($chk->severity) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endforeach
</div>
@else
<p class="text-gray-500">Nog geen inspectielijst gevonden.</p>
@endif
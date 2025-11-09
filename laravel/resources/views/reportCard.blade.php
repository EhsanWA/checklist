@if ($report->inspectionList && $report->inspectionList->categories->count())
    @foreach ($report->inspectionList->categories as $category)
        @foreach ($category->checks as $check)
            @php
                $severity = strtolower($check->severity ?? '');
                $severityClasses = [
                    'hoog' => 'border-red-200 bg-red-50 text-red-700',
                    'kritisch' => 'border-red-200 bg-red-50 text-red-700',
                    'medium' => 'border-amber-200 bg-amber-50 text-amber-700',
                    'laag' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                ];
                $badgeClass = $severityClasses[$severity] ?? 'border-slate-200 bg-slate-50 text-slate-700';
            @endphp

            <article class="mb-3 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm last:mb-0 sm:flex-row sm:items-center sm:justify-between"
                data-check-item="check-{{ $check->id }}" data-status="opdrachten">
                <div class="flex-1">
                    <p class="text-xs uppercase tracking-wide text-slate-500">{{ $category->name }}</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $check->label ?? 'Controle #' . $check->id }}</p>
                    <div class="mt-1 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                        @if ($check->code)
                            <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">
                                <i class="fa-solid fa-hashtag text-[10px]"></i> {{ $check->code }}
                            </span>
                        @endif
                        @if ($check->severity)
                            <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-xs font-semibold {{ $badgeClass }}">
                                <i class="fa-solid fa-bolt text-[10px]"></i> {{ ucfirst($check->severity) }}
                            </span>
                        @endif
                        @if ($check->required)
                            <span class="inline-flex items-center gap-1 rounded-full bg-sky-50 px-2 py-0.5 text-xs font-semibold text-sky-700">
                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i> Verplicht
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button"
                        class="rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100"
                        data-move-to="gecontroleerd" title="Markeer als gecontroleerd">
                        V
                    </button>
                    <button type="button"
                        class="rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 transition hover:bg-rose-100"
                        data-move-to="bijzonderheden" title="Verplaats naar bijzonderheden">
                        X
                    </button>
                </div>
            </article>
        @endforeach
    @endforeach
@endif

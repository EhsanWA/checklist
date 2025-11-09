@php
    use Illuminate\Support\Facades\Storage;
@endphp

@if ($report->inspectionList && $report->inspectionList->categories->count())
    @foreach ($report->inspectionList->categories as $category)
        @foreach ($category->checks as $check)
            @php
                $state = $checkItems[$check->id] ?? null;
                $currentStatus = old('checks.' . $check->id . '.status', $state->status ?? 'pending');
                $notesValue = old('checks.' . $check->id . '.notes', $state->notes ?? '');
                $existingPhotos = $state->photos ?? [];
                $severity = strtolower($check->severity ?? '');
                $severityClasses = [
                    'hoog' => 'border-red-200 bg-red-50 text-red-700',
                    'kritisch' => 'border-red-200 bg-red-50 text-red-700',
                    'medium' => 'border-amber-200 bg-amber-50 text-amber-700',
                    'laag' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                ];
                $badgeClass = $severityClasses[$severity] ?? 'border-slate-200 bg-slate-50 text-slate-700';
                $showDetails = $currentStatus === 'bijzonderheden';
            @endphp

            <article class="mb-3 space-y-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm last:mb-0"
                data-check-item="{{ $check->id }}" data-status="{{ $currentStatus }}">
                <input type="hidden" name="checks[{{ $check->id }}][status]" value="{{ $currentStatus }}" data-status-field>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
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
                </div>

                <div @class([
                    'rounded-2xl border border-slate-100 bg-slate-50/60 p-3 text-sm text-slate-600',
                    'hidden' => ! $showDetails,
                ]) data-note-wrapper>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Bijzonderheden / extra toelichting
                    </label>
                    <textarea rows="3" name="checks[{{ $check->id }}][notes]" data-note-field
                        class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-800 focus:border-sky-400 focus:ring focus:ring-sky-100"
                        placeholder="Omschrijf wat er is opgemerkt...">{{ $notesValue }}</textarea>
                    @error('checks.' . $check->id . '.notes')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror

                    <div class="mt-3">
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Foto's toevoegen
                        </label>
                        <input type="file" name="checks[{{ $check->id }}][photos][]" multiple accept="image/*" data-photo-field
                            class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-sky-100 file:px-3 file:py-2 file:text-sky-700 hover:file:bg-sky-200">
                        @error('checks.' . $check->id . '.photos.*')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror

                        @if (!empty($existingPhotos))
                            <div class="mt-2 flex flex-wrap gap-3 text-xs">
                                @foreach ($existingPhotos as $photo)
                                    <a href="{{ Storage::url($photo) }}" target="_blank"
                                        class="inline-flex items-center gap-1 rounded-full bg-white px-3 py-1 font-medium text-sky-600 underline">
                                        <i class="fa-solid fa-paperclip text-[10px]"></i>
                                        Foto {{ $loop->iteration }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach
    @endforeach
@endif

@php
// Render individuele check item met status (Open/Gereed/Bijzonder), notes textarea, foto inputs. Form inputs sync naar server. Drag disabled via app.js.
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
$showDetails = $currentStatus === 'bijzonderheden' || !empty($notesValue);
$statusOptions = [
'pending' => ['label' => 'Open', 'hint' => 'Nog uitvoeren'],
'gecontroleerd' => ['label' => 'Gereed', 'hint' => 'Afgevinkt'],
'bijzonderheden' => ['label' => 'Bijzonder', 'hint' => 'Notitie/foto'],
];
$searchIndex = Str::of(
implode(' ', [
$category->name,
$check->label ?? '',
$check->code ?? '',
$check->severity ?? '',
])
)->lower()->squish();
@endphp

{{-- Data-attributes: data-check-item (ID), data-status (JS state), data-search-index (zoekmatch). Form inputs: checks[id][status/notes/photos]. --}}
<article class="mb-4 space-y-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm last:mb-0"
    data-check-item="check-{{ $check->id }}" data-status="{{ $currentStatus }}" tabindex="0"
    data-search-index="{{ $searchIndex }}">
    <input type="hidden" name="checks[{{ $check->id }}][status]" value="{{ $currentStatus }}"
        data-status-field>

    <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
        <div class="flex-1">
            <p class="text-xs uppercase tracking-wide text-slate-500">{{ $category->name }}</p>
            <p class="text-lg font-semibold text-slate-900">
                {{ $check->label ?? 'Controle #' . $check->id }}
            </p>
            <div class="mt-1 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                @if ($check->code)
                <span
                    class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">
                    <i class="fa-solid fa-hashtag text-[10px]"></i> {{ $check->code }}
                </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Drie status pills: Open/Gereed/Bijzonder. Data-status-option triggers moveItem() in tabblad.blade JS. --}}
    <div class="flex flex-wrap items-center gap-2">
        @foreach ($statusOptions as $key => $meta)
        <button type="button"
            class="status-pill rounded-full border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-600 transition focus:outline-none focus:ring focus:ring-sky-200 min-h-[44px]"
            data-status-option="{{ $key }}">
            <span class="block">{{ $meta['label'] }}</span>
        </button>
        @endforeach
    </div>

    {{-- Details panel (verborgen tot status=bijzonderheden): textarea voor notes + duo foto inputs (bestandskiezer & camera). --}}
    <div @class([ 'rounded-2xl border border-slate-100 bg-white p-3 text-sm text-slate-600 shadow-inner' , 'hidden'=> !$showDetails,
        ]) data-note-wrapper>
        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">
            Bijzonderheden / extra toelichting
        </label>
        <textarea rows="4" name="checks[{{ $check->id }}][notes]" data-note-field
            class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-base text-slate-800 focus:border-sky-400 focus:ring focus:ring-sky-100"
            placeholder="Omschrijf wat er is opgemerkt...">{{ $notesValue }}</textarea>
        @error('checks.' . $check->id . '.notes')
        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror

        <div class="mt-4 space-y-4">
            <div class="space-y-2">
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Foto's toevoegen
                </label>
                <input type="file" name="checks[{{ $check->id }}][photos][]" multiple accept="image/*"
                    data-photo-field
                    class="block w-full rounded-lg border border-dashed border-slate-300 px-3 py-10 text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-sky-100 file:px-3 file:py-2 file:text-sky-700 hover:border-sky-300">
                @error('checks.' . $check->id . '.photos.*')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Foto maken met camera
                </label>
                <input type="file" name="checks[{{ $check->id }}][photos][]" accept="image/*;capture=camera"
                    capture="environment"
                    class="block w-full rounded-lg border border-dashed border-slate-300 px-3 py-6 text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-sky-100 file:px-3 file:py-2 file:text-sky-700 hover:border-sky-300">
                <p class="text-xs text-slate-400">Op mobiel opent dit de camera; op desktop zie je de bestandskiezer.</p>
            </div>

            @if (!empty($existingPhotos))
            <div class="flex flex-wrap gap-3 text-xs">
                @foreach ($existingPhotos as $photo)
                <a href="{{ Storage::url($photo) }}" target="_blank"
                    class="inline-flex items-center gap-1 rounded-full bg-sky-50 px-3 py-1 font-medium text-sky-700 underline">
                    <i class="fa-solid fa-paperclip text-[10px]"></i>
                    Bestaand bestand {{ $loop->iteration }}
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
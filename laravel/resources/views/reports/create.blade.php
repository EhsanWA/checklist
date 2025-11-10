<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuwe rapportage</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen">
    <header class="bg-sky-500 p-4 drop-shadow-md">
        <a href="{{ route('reports.beheer') }}" class="text-white underline">← Terug</a>
    </header>

    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-8 mt-10">
        <h1 class="text-2xl font-semibold mb-6 text-sky-700">Nieuwe rapportage</h1>

        <form id="report-create-form" method="POST" action="{{ route('reports.store') }}" class="space-y-6 pb-32">
            @csrf

            {{-- Schip naam --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Schip naam <span
                        class="text-red-500">*</span></label>
                <input type="text" name="schip_naam" value="{{ old('schip_naam') }}" required
                    placeholder="Bijv. Zr.Ms. Rotterdam"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 px-4 py-2 text-gray-800 placeholder-gray-400">
                @error('schip_naam')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Schip nummer --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Schip nummer</label>
                <input type="text" name="schip_nummer" value="{{ old('schip_nummer') }}" placeholder="Bijv. F801"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 px-4 py-2 text-gray-800 placeholder-gray-400">
                @error('schip_nummer')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Schip bouwjaar --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Schip bouwjaar</label>
                <input type="number" name="schip_bouwjaar" value="{{ old('schip_bouwjaar') }}" placeholder="Bijv. 2012"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 px-4 py-2 text-gray-800 placeholder-gray-400">
                @error('schip_bouwjaar')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Monteur --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Monteur <span
                        class="text-red-500">*</span></label>
                <input type="text" name="monteur" value="{{ old('monteur') }}" required
                    placeholder="Naam van de monteur"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 px-4 py-2 text-gray-800 placeholder-gray-400">
                @error('monteur')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Beschrijving --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Beschrijving</label>
                <textarea name="description" rows="5" placeholder="Bijv. Details over onderhoud, problemen of opmerkingen..."
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 px-4 py-2 text-gray-800 placeholder-gray-400">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 px-4 py-2 text-gray-800">
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Concept</option>
                    <option value="submitted" {{ old('status') === 'submitted' ? 'selected' : '' }}>Ingediend</option>
                    <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Gearchiveerd</option>
                </select>
                @error('status')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Koppel inspectielijst --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Inspectielijst (optioneel)
                </label>
                <select name="inspection_list_id"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 px-4 py-2">
                    <option value="">— Geen —</option>
                    @foreach ($inspections as $ins)
                        <option value="{{ $ins->id }}" @selected(old('inspection_list_id') == $ins->id)>
                            {{ $ins->title ?? 'Inspectie #' . $ins->id }}
                        </option>
                    @endforeach
                </select>
                @error('inspection_list_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

        </form>
    </div>

    <div class="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-2xl items-center justify-between gap-3 px-4 py-3">
            <a href="{{ route('reports.index') }}"
                class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 font-medium transition">
                Annuleren
            </a>
            <button form="report-create-form"
                class="px-6 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white font-semibold shadow-sm transition">
                Opslaan
            </button>
        </div>
    </div>
</body>

</html>


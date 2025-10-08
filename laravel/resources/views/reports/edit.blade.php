<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $report->schip_naam }} – Bewerken</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen">
    <header class="bg-sky-500 p-4 drop-shadow-md">
        <a href="{{ route('reports.index') }}" class="text-white underline">← Terug</a>
    </header>

    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-8 mt-10">
        <h1 class="text-2xl font-semibold mb-6 text-sky-700">Rapportage bewerken</h1>

        <form method="POST" action="{{ route('reports.update', $report) }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Schip naam --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Schip naam <span
                        class="text-red-500">*</span></label>
                <input type="text" name="schip_naam" value="{{ old('schip_naam', $report->schip_naam) }}" required
                    placeholder="Bijv. Zr.Ms. Rotterdam"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 px-4 py-2 text-gray-800 placeholder-gray-400">
                @error('schip_naam')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Schip nummer --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Schip nummer</label>
                <input type="text" name="schip_nummer" value="{{ old('schip_nummer', $report->schip_nummer) }}"
                    placeholder="Bijv. F801"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 px-4 py-2 text-gray-800 placeholder-gray-400">
                @error('schip_nummer')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Schip bouwjaar --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Schip bouwjaar</label>
                <input type="number" name="schip_bouwjaar" value="{{ old('schip_bouwjaar', $report->schip_bouwjaar) }}"
                    placeholder="Bijv. 2012"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 px-4 py-2 text-gray-800 placeholder-gray-400">
                @error('schip_bouwjaar')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Monteur --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Monteur <span
                        class="text-red-500">*</span></label>
                <input type="text" name="monteur" value="{{ old('monteur', $report->monteur) }}" required
                    placeholder="Naam van de monteur"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 px-4 py-2 text-gray-800 placeholder-gray-400">
                @error('monteur')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Beschrijving --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Beschrijving</label>
                <textarea name="description" rows="5" placeholder="Bijv. details over onderhoud, problemen of opmerkingen..."
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 px-4 py-2 text-gray-800 placeholder-gray-400">{{ old('description', $report->description) }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 px-4 py-2 text-gray-800">
                    <option value="draft" {{ old('status', $report->status) === 'draft' ? 'selected' : '' }}>Concept
                    </option>
                    <option value="submitted" {{ old('status', $report->status) === 'submitted' ? 'selected' : '' }}>
                        Ingediend</option>
                    <option value="archived" {{ old('status', $report->status) === 'archived' ? 'selected' : '' }}>
                        Gearchiveerd</option>
                </select>
                @error('status')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Knoppen --}}
            <div class="flex gap-3 pt-4">
                <a href="{{ route('reports.index') }}"
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium transition">
                    Annuleren
                </a>
                <button
                    class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-sm transition">
                    Wijzigingen opslaan
                </button>
            </div>
        </form>
    </div>
</body>

</html>

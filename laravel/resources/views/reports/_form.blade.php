@csrf
<div class="space-y-6">

    {{-- Schip naam --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Schip naam <span class="text-red-500">*</span>
        </label>
        <input type="text" name="schip_naam" value="{{ old('schip_naam', $report->schip_naam ?? '') }}" required
            placeholder="Bijv. Zr.Ms. Rotterdam"
            class="w-full rounded-lg border-gray-300 shadow-sm
                      focus:border-sky-500 focus:ring focus:ring-sky-200
                      focus:ring-opacity-50 px-4 py-2 text-gray-800 placeholder-gray-400">
        @error('schip_naam')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Schip nummer --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Schip nummer</label>
        <input type="text" name="schip_nummer" value="{{ old('schip_nummer', $report->schip_nummer ?? '') }}"
            placeholder="Bijv. F801"
            class="w-full rounded-lg border-gray-300 shadow-sm
                      focus:border-sky-500 focus:ring focus:ring-sky-200
                      focus:ring-opacity-50 px-4 py-2 text-gray-800 placeholder-gray-400">
        @error('schip_nummer')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Schip bouwjaar --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Schip bouwjaar</label>
        <input type="number" name="schip_bouwjaar" value="{{ old('schip_bouwjaar', $report->schip_bouwjaar ?? '') }}"
            placeholder="Bijv. 2012"
            class="w-full rounded-lg border-gray-300 shadow-sm
                      focus:border-sky-500 focus:ring focus:ring-sky-200
                      focus:ring-opacity-50 px-4 py-2 text-gray-800 placeholder-gray-400">
        @error('schip_bouwjaar')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Monteur --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Monteur <span class="text-red-500">*</span>
        </label>
        <input type="text" name="monteur" value="{{ old('monteur', $report->monteur ?? '') }}" required
            placeholder="Naam van de monteur"
            class="w-full rounded-lg border-gray-300 shadow-sm
                      focus:border-sky-500 focus:ring focus:ring-sky-200
                      focus:ring-opacity-50 px-4 py-2 text-gray-800 placeholder-gray-400">
        @error('monteur')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Beschrijving --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Beschrijving</label>
        <textarea name="description" rows="5" placeholder="Bijv. details over onderhoud, problemen of opmerkingen..."
            class="w-full rounded-lg border-gray-300 shadow-sm
                         focus:border-sky-500 focus:ring focus:ring-sky-200
                         focus:ring-opacity-50 px-4 py-2 text-gray-800 placeholder-gray-400">{{ old('description', $report->description ?? '') }}</textarea>
        @error('description')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Status --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
        @php $status = old('status', $report->status ?? 'draft'); @endphp
        <select name="status"
            class="w-full rounded-lg border-gray-300 shadow-sm
                       focus:border-sky-500 focus:ring focus:ring-sky-200
                       focus:ring-opacity-50 px-4 py-2 text-gray-800">
            <option value="draft" @selected($status === 'draft')>Open</option>
            <option value="submitted" @selected($status === 'submitted')>Ingediend</option>
            <option value="archived" @selected($status === 'archived')>Gearchiveerd</option>
        </select>
    </div>

</div>

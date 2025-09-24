@csrf
<div class="space-y-6">

    {{-- Titel --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Titel <span class="text-red-500">*</span></label>
        <input name="title" value="{{ old('title', $report->title ?? '') }}" required
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 focus:ring-opacity-50 px-4 py-2 text-gray-800 placeholder-gray-400"
            placeholder="Bijv. Rapportage missie X">
        @error('title')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Beschrijving --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Beschrijving</label>
        <textarea name="description" rows="5"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 focus:ring-opacity-50 px-4 py-2 text-gray-800 placeholder-gray-400"
            placeholder="Schrijf hier je rapportage...">{{ old('description', $report->description ?? '') }}</textarea>
        @error('description')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Status --}}    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
        @php $status = old('status', $report->status ?? 'draft'); @endphp
        <select name="status"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 focus:ring-opacity-50 px-4 py-2 text-gray-800">
            <option value="draft" @selected($status === 'draft')>Concept</option>
            <option value="submitted" @selected($status === 'submitted')>Ingediend</option>
            <option value="archived" @selected($status === 'archived')>Gearchiveerd</option>
        </select>
    </div>

</div>

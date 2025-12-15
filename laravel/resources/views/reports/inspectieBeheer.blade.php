<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beheer - Inspectielijsten</title>
    @vite('resources/css/app.css')
    <script src="https://kit.fontawesome.com/21e98e6012.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-50 text-gray-900">
    @include('header')

    @php
        $inspectionLists = $inspectionLists ?? collect();
        $q = $q ?? request('q');
        $status = $status ?? request('status', 'all');
        $sort = $sort ?? request('sort', 'created_desc');
        $perPage = $perPage ?? request('per_page', 12);
    @endphp

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        {{-- Titel + actieknoppen --}}
        <header class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-semibold tracking-tight">Inspectielijsten</h1>
                <p class="text-gray-600">Beheer, filter en raadpleeg alle beschikbare inspectielijsten.</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('inspections.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-2 font-medium hover:bg-green-700 shadow transition w-full sm:w-auto justify-center">
                    <i class="fa-solid fa-plus"></i>
                    <span>Inspectielijst aanmaken</span>
                </a>

                <a href="{{ route('reports.beheer') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-sky-600 text-white px-4 py-2 font-medium hover:bg-sky-700 shadow transition w-full sm:w-auto justify-center">
                    <i class="fa-solid fa-clipboard-list"></i>
                    <span>Naar rapportages</span>
                </a>
            </div>
        </header>

        {{-- Tabs --}}
        @php
            $defaultTotal = method_exists($inspectionLists, 'total') ? $inspectionLists->total() : ($inspectionLists->count() ?? 0);
            $tabs = $tabs ?? [
                'all' => ['label' => 'Alle lijsten', 'count' => $counts['all'] ?? $defaultTotal],
                'with_categories' => ['label' => 'Met categorieen', 'count' => $counts['with_categories'] ?? 0],
                'without_categories' => ['label' => 'Zonder categorieen', 'count' => $counts['without_categories'] ?? 0],
            ];
            $active = array_key_exists($status, $tabs) ? $status : array_key_first($tabs);
        @endphp

        

        {{-- Filters --}}
        <form method="GET" action="{{ request()->url() }}" class="bg-white rounded-2xl shadow p-5">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zoeken</label>
                    <input type="text" name="q" value="{{ $q }}"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-200 px-3 py-2"
                        placeholder="Zoek op titel of beschrijving">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-200 px-3 py-2">
                        @foreach ($tabs as $key => $tab)
                            <option value="{{ $key }}" {{ $active === $key ? 'selected' : '' }}>
                                {{ $tab['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sorteren</label>
                    <select name="sort"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-200 px-3 py-2">
                        <option value="created_desc" {{ $sort === 'created_desc' ? 'selected' : '' }}>Nieuwste eerst</option>
                        <option value="created_asc" {{ $sort === 'created_asc' ? 'selected' : '' }}>Oudste eerst</option>
                        <option value="title_asc" {{ $sort === 'title_asc' ? 'selected' : '' }}>Titel A-Z</option>
                        <option value="title_desc" {{ $sort === 'title_desc' ? 'selected' : '' }}>Titel Z-A</option>
                        <option value="categories_desc" {{ $sort === 'categories_desc' ? 'selected' : '' }}>Meeste categorieën</option>
                        <option value="categories_asc" {{ $sort === 'categories_asc' ? 'selected' : '' }}>Minste categorieën</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">lijsten per pagina</label>
                    <select name="per_page"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-200 px-3 py-2">
                        @foreach ([10, 12, 24, 50] as $size)
                            <option value="{{ $size }}" {{ (int) $perPage === $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-5 flex items-center gap-2">
                <button type="submit"
                    class="inline-flex items-center rounded-lg bg-sky-600 text-white px-4 py-2 font-medium hover:bg-sky-700">
                    Toepassen
                </button>
                <a href="{{ request()->url() }}"
                    class="inline-flex items-center rounded-lg bg-gray-100 text-gray-800 px-4 py-2 font-medium hover:bg-gray-200">
                    Reset
                </a>
            </div>
        </form>

        {{-- Lijsten overzicht --}}
        <section class="bg-white rounded-2xl shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Titel</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Beschrijving</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Categorieën</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Aangemaakt</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($inspectionLists as $list)
                            @php
                                $categoryCount = $list->categories_count ?? ($list->categories->count() ?? 0);
                                $checkCount = $list->checks_count
                                    ?? ($list->categories
                                        ? $list->categories->sum(fn($category) => $category->checks?->count() ?? 0)
                                        : 0);
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-gray-900">{{ $list->title ?? '—' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ $list->description ? \Illuminate\Support\Str::limit($list->description, 80) : 'Geen omschrijving' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700">
                                        {{ $categoryCount }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ optional($list->created_at)->format('d-m-Y H:i') ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex flex-col sm:flex-row gap-2 justify-end">
                                        <a href="{{ route('inspections.edit', $list) }}"
                                            class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-100">
                                            Bewerken
                                        </a>
                                        <form action="{{ route('inspections.destroy', $list) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Weet je zeker dat je deze inspectielijst wilt verwijderen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center rounded-lg bg-red-600 text-white px-3 py-1.5 text-sm font-medium hover:bg-red-700">
                                                Verwijderen
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    Nog geen inspectielijsten gevonden.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($inspectionLists, 'links'))
                <div class="px-4 py-3 border-t border-gray-100">
                    {{ $inspectionLists->links() }}
                </div>
            @endif
        </section>
    </main>
</body>

</html>

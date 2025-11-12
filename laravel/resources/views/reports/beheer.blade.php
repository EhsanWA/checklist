<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beheer - Rapportages</title>
    @vite('resources/css/app.css')
    <script src="https://kit.fontawesome.com/21e98e6012.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-50 text-gray-900">

    @include('header')

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Titel + actieknop --}}
        <header class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-semibold tracking-tight">Beheer</h1>
                <p class="text-gray-600">Overzicht met filters, tabs en beheeracties.</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('inspections.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-2 font-medium hover:green-sky-800 shadow transition w-full sm:w-auto justify-center">
                    <i class="fa-solid fa-plus"></i>
                    <span>Inspectie lijst aanmaken</span>
                </a>

                <a href="{{ route('reports.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-sky-600 text-white px-4 py-2 font-medium hover:bg-sky-700 shadow transition w-full sm:w-auto justify-center">
                    <i class="fa-solid fa-plus"></i>
                    <span>Rapportage aanmaken</span>
                </a>
            </div>
        </header>

        {{-- Tabs --}}
        @php
            $tabs = [
                'all' => ['label' => 'Alles', 'count' => $counts['all'] ?? 0],
                'draft' => ['label' => 'Concepten', 'count' => $counts['draft'] ?? 0],
                'submitted' => ['label' => 'Ingediend', 'count' => $counts['submitted'] ?? 0],
                'archived' => ['label' => 'Archief', 'count' => $counts['archived'] ?? 0],
            ];
            $active = $status ?? 'all';
        @endphp

        <nav class="flex flex-wrap gap-2">
            @foreach ($tabs as $key => $tab)
                <a href="{{ route('reports.beheer', array_merge(request()->except('page'), ['status' => $key])) }}"
                    class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm
                          {{ $active === $key ? 'bg-sky-600 text-white' : 'bg-white border text-gray-700 hover:bg-gray-50' }}">
                    <span>{{ $tab['label'] }}</span>
                    <span
                        class="inline-flex items-center justify-center min-w-6 h-6 rounded-full text-xs
                                 {{ $active === $key ? 'bg-white/20' : 'bg-gray-100 text-gray-700' }}">
                        {{ $tab['count'] }}
                    </span>
                </a>
            @endforeach
        </nav>

        {{-- Filters --}}
        <form method="GET" action="{{ route('reports.beheer') }}" class="bg-white rounded-2xl shadow p-5">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zoeken</label>
                    <input type="text" name="q" value="{{ $q }}"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-200 px-3 py-2"
                        placeholder="Zoek op schip, nummer, monteur of beschrijving">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-200 px-3 py-2">
                        @foreach (array_keys($tabs) as $key)
                            <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>
                                {{ $tabs[$key]['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sorteren</label>
                    <select name="sort"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-200 px-3 py-2">
                        <option value="created_desc" {{ $sort === 'created_desc' ? 'selected' : '' }}>Nieuwste eerst
                        </option>
                        <option value="created_asc" {{ $sort === 'created_asc' ? 'selected' : '' }}>Oudste eerst
                        </option>
                        <option value="schip_naam_asc" {{ $sort === 'schip_naam_asc' ? 'selected' : '' }}>Schip A-Z
                        </option>
                        <option value="schip_naam_desc"{{ $sort === 'schip_naam_desc' ? 'selected' : '' }}>Schip Z-A
                        </option>
                        <option value="bouwjaar_asc" {{ $sort === 'bouwjaar_asc' ? 'selected' : '' }}>Bouwjaar ↑
                        </option>
                        <option value="bouwjaar_desc" {{ $sort === 'bouwjaar_desc' ? 'selected' : '' }}>Bouwjaar ↓
                        </option>
                        <option value="nummer_asc" {{ $sort === 'nummer_asc' ? 'selected' : '' }}>Nummer ↑</option>
                        <option value="nummer_desc" {{ $sort === 'nummer_desc' ? 'selected' : '' }}>Nummer ↓</option>
                        <option value="monteur_asc" {{ $sort === 'monteur_asc' ? 'selected' : '' }}>Monteur A-Z
                        </option>
                        <option value="monteur_desc" {{ $sort === 'monteur_desc' ? 'selected' : '' }}>Monteur Z-A
                        </option>
                    </select>
                </div>
            </div>

            <div class="mt-5 flex items-center gap-2">
                <button type="submit"
                    class="inline-flex items-center rounded-lg bg-sky-600 text-white px-4 py-2 font-medium hover:bg-sky-700">
                    Toepassen
                </button>
                <a href="{{ route('reports.beheer') }}"
                    class="inline-flex items-center rounded-lg bg-gray-100 text-gray-800 px-4 py-2 font-medium hover:bg-gray-200">
                    Reset
                </a>
            </div>
        </form>

        {{-- Tabel --}}
        <section class="bg-white rounded-2xl shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Schip</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nummer</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Bouwjaar</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Monteur</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Aangemaakt
                            </th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($reports as $report)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium">{{ $report->schip_naam ?? '—' }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $report->schip_nummer ?? '—' }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $report->schip_bouwjaar ?? '—' }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $report->monteur ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $status = $report->status ?? '';
                                        $badge = match ($status) {
                                            'draft' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
                                            'submitted' => 'bg-sky-50 text-sky-800 ring-sky-600/20',
                                            'archived' => 'bg-gray-100 text-gray-800 ring-gray-500/20',
                                            default => 'bg-gray-50 text-gray-700 ring-gray-600/10',
                                        };
                                        $label =
                                            [
                                                'draft' => 'Concept',
                                                'submitted' => 'Ingediend',
                                                'archived' => 'Archief',
                                            ][$status] ?? ucfirst($status ?: '—');
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 {{ $badge }}">
                                        {{ $label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ optional($report->created_at)->format('d-m-Y H:i') ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('reports.edit', $report) }}"
                                        class="mr-2 inline-flex items-center rounded-lg border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-100">
                                        Bewerken
                                    </a>
                                    <form action="{{ route('reports.destroy', $report) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Weet je zeker dat je dit rapport wilt verwijderen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center rounded-lg bg-red-600 text-white px-3 py-1.5 text-sm font-medium hover:bg-red-700">
                                            Verwijderen
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    Geen rapportages gevonden.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-gray-100">
                {{ $reports->links() }}
            </div>
        </section>
    </main>
</body>

</html>

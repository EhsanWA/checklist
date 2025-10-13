<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50 text-gray-900">

    {{-- Header --}}
    @include('header')

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <header class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-semibold tracking-tight">Rapportages</h1>
                <p class="text-gray-600">Iedereen kan rapportages bekijken en openen.</p>
            </div>
        </header>

        {{-- Filters op jouw velden --}}
        <form method="GET" action="{{ route('reports.index') }}" class="bg-white rounded-2xl shadow p-5">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Schip naam</label>
                    <input type="text" name="schip_naam" value="{{ request('schip_naam') }}"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-200 px-3 py-2"
                        placeholder="Bijv. Zr.Ms. ...">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Schip nummer</label>
                    <input type="text" name="schip_nummer" value="{{ request('schip_nummer') }}"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-200 px-3 py-2"
                        placeholder="Bijv. M847">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bouwjaar</label>
                    <input type="number" name="schip_bouwjaar" value="{{ request('schip_bouwjaar') }}" min="1800"
                        max="{{ now()->year + 1 }}"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-200 px-3 py-2"
                        placeholder="{{ now()->year }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Monteur</label>
                    <input type="text" name="monteur" value="{{ request('monteur') }}"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-200 px-3 py-2"
                        placeholder="Naam monteur">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-200 px-3 py-2">
                        @php $s = request('status', ''); @endphp
                        <option value="" {{ $s === '' ? 'selected' : '' }}>Alles</option>
                        <option value="draft" {{ $s === 'draft' ? 'selected' : '' }}>Concept</option>
                        <option value="submitted" {{ $s === 'submitted' ? 'selected' : '' }}>Ingediend</option>
                        <option value="archived" {{ $s === 'archived' ? 'selected' : '' }}>Archief</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Beschrijving bevat</label>
                    <input type="text" name="description" value="{{ request('description') }}"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-200 px-3 py-2"
                        placeholder="Zoekterm in beschrijving">
                </div>

                <div class="md:col-span-3 grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vanaf (aangemaakt)</label>
                        <input type="date" name="from" value="{{ request('from') }}"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-200 px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tot (aangemaakt)</label>
                        <input type="date" name="to" value="{{ request('to') }}"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-200 px-3 py-2">
                    </div>
                </div>
            </div>

            <div class="mt-5 flex items-center gap-2">
                <button type="submit"
                    class="inline-flex items-center rounded-lg bg-sky-600 text-white px-4 py-2 font-medium shadow hover:bg-sky-700 transition">
                    Filters toepassen
                </button>
                <a href="{{ route('reports.index') }}"
                    class="inline-flex items-center rounded-lg bg-gray-100 text-gray-800 px-4 py-2 font-medium hover:bg-gray-200 transition">
                    Reset
                </a>
            </div>
        </form>

        {{-- Lijst --}}
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
                                <td class="px-6 py-4">
                                    <div class="font-medium">
                                        <a href="{{ route('reports.show', $report) }}" class="hover:underline">
                                            {{ $report->schip_naam ?? '—' }}
                                        </a>
                                    </div>
                                    @if (!empty($report->description))
                                        <div class="text-gray-500 text-sm line-clamp-2">
                                            {{ \Illuminate\Support\Str::limit($report->description, 120) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ $report->schip_nummer ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ $report->schip_bouwjaar ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ $report->monteur ?? '—' }}
                                </td>
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
                                    <a href="{{ route('reports.show', $report) }}"
                                        class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-100">
                                        Bekijken
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">Geen rapportages gevonden met deze filters.</div>
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

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koninklijke Marine – Rapportages</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">

    @include('header')

    <main class="flex-grow flex flex-col items-center justify-start bg-blue-100 py-8">
        <div class="bg-white shadow-md rounded-lg w-full max-w-5xl">
            {{-- Header --}}
            <div class="px-6 pt-6 text-center">
                @if (session('success'))
                    <div class="mb-4 rounded bg-green-100 text-green-800 px-3 py-2 text-sm">{{ session('success') }}
                    </div>
                @endif
                <h2 class="text-2xl font-bold">Rapportages</h2>
                <p class="text-gray-600 mb-4">Zoek, filter en open een rapportage.</p>
            </div>

            {{-- Tabs --}}
            @php
                $tabs = [
                    'all' => ['label' => 'Alles', 'count' => $counts['all']],
                    'draft' => ['label' => 'Concepten', 'count' => $counts['draft']],
                    'submitted' => ['label' => 'Ingediend', 'count' => $counts['submitted']],
                    'archived' => ['label' => 'Archief', 'count' => $counts['archived']],
                ];
            @endphp
            <div class="px-4 sm:px-6">
                <div class="flex gap-2 overflow-x-auto pb-2 -mx-2 px-2">
                    @foreach ($tabs as $key => $tab)
                        <a href="{{ route('reports.index', array_merge(request()->except('page'), ['status' => $key])) }}"
                            class="whitespace-nowrap px-3 py-2 rounded-lg text-sm border
                    {{ $status === $key ? 'bg-sky-500 text-white border-sky-500' : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100' }}">
                            {{ $tab['label'] }}
                            <span
                                class="ml-2 inline-block text-xs px-2 py-0.5 rounded-full
                        {{ $status === $key ? 'bg-white text-sky-700' : 'bg-gray-200 text-gray-700' }}">
                                {{ $tab['count'] }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Toolbar: search + sort + perPage + add --}}
            <form method="GET" action="{{ route('reports.index') }}" class="px-4 sm:px-6 mt-4">
                {{-- behoud status in query --}}
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">

                    <input name="q" value="{{ $q }}" placeholder="Zoek op titel of beschrijving..."
                        class="flex-1 rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-sky-200 focus:border-sky-500 px-4 py-2">

                    <select name="sort"
                        class="rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-sky-200 focus:border-sky-500 px-3 py-2">
                        <option value="created_desc" @selected($sort === 'created_desc')>Nieuwste eerst</option>
                        <option value="created_asc" @selected($sort === 'created_asc')>Oudste eerst</option>
                        <option value="title_asc" @selected($sort === 'title_asc')>Titel A–Z</option>
                        <option value="title_desc" @selected($sort === 'title_desc')>Titel Z–A</option>
                    </select>

                    <select name="per_page"
                        class="rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-sky-200 focus:border-sky-500 px-3 py-2">
                        @foreach ([6, 12, 18, 24, 36] as $n)
                            <option value="{{ $n }}" @selected($perPage == $n)>{{ $n }}/pagina
                            </option>
                        @endforeach
                    </select>

                    <button
                        class="rounded-lg bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 font-medium">Toepassen</button>

                    <a href="{{ route('reports.create') }}"
                        class="rounded-lg bg-green-500 hover:bg-green-600 text-white px-4 py-2 font-semibold text-center">
                        + Nieuwe
                    </a>
                </div>
            </form>

            {{-- Grid --}}
            <div class="px-4 sm:px-6 py-6">
                @if ($reports->count())
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($reports as $report)
                            <div class="rounded-lg border border-gray-200 bg-white shadow-sm hover:shadow transition">
                                <a href="{{ route('reports.show', $report) }}" class="block p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <h3 class="font-semibold text-gray-900 line-clamp-2">{{ $report->title }}</h3>
                                        <span
                                            class="text-xs px-2 py-0.5 rounded-full
                    @class([
                        'bg-gray-200 text-gray-700' => $report->status === 'draft',
                        'bg-green-100 text-green-700' => $report->status === 'submitted',
                        'bg-gray-300 text-gray-800' => $report->status === 'archived',
                    ])">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </div>
                                    @if ($report->description)
                                        <p class="mt-2 text-sm text-gray-600 line-clamp-3">{{ $report->description }}
                                        </p>
                                    @endif
                                    <p class="mt-3 text-xs text-gray-500">
                                        {{ $report->created_at->format('d-m-Y H:i') }}</p>
                                </a>

                                <div class="flex gap-2 p-3 border-t border-gray-100">
                                    <a href="{{ route('reports.edit', $report) }}"
                                        class="flex-1 text-center rounded bg-blue-600 hover:bg-blue-700 text-white text-sm py-1.5">Bewerken</a>
                                    <form method="POST" action="{{ route('reports.destroy', $report) }}"
                                        class="flex-1"
                                        onsubmit="return confirm('‘{{ $report->title }}’ verwijderen?');">
                                        @csrf @method('DELETE')
                                        <button
                                            class="w-full text-center rounded bg-red-600 hover:bg-red-700 text-white text-sm py-1.5">Verwijderen</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 px-2">
                        {{ $reports->onEachSide(1)->links() }}
                    </div>
                @else
                    <div class="text-center text-gray-500 py-16">
                        Geen rapportages gevonden. Pas je filters/zoekopdracht aan.
                    </div>
                @endif
            </div>
        </div>
    </main>
</body>

</html>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koninklijke Marine – Rapportages</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">

    @include('components.header')

    <main class="flex-grow flex flex-col items-center justify-center bg-blue-100">
        <div class="bg-white shadow-md rounded-lg p-10 text-center w-full max-w-5xl">
            @if (session('success'))
                <div class="mb-4 rounded bg-green-100 text-green-800 px-3 py-2 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <h2 class="text-2xl font-bold mb-2">Welkom</h2>
            <p class="text-gray-600 mb-8">Kies hieronder welke rapportage je wilt openen.</p>

            {{-- Open rapportages --}}
            <div class="mb-10 text-left">
                <h3 class="text-xl font-semibold text-sky-600 mb-4">Open</h3>
                @if ($drafts->count())
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach ($drafts as $report)
                            <a href="{{ route('reports.show', $report) }}"
                                class="bg-gray-100 hover:bg-gray-200 rounded-lg p-4 text-gray-800 block shadow-sm">
                                <div class="font-medium">{{ $report->title }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $report->created_at->format('d-m-Y H:i') }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Geen open rapportages beschikbaar.</p>
                @endif
            </div>

            {{-- Ingediende --}}
            <div class="mb-10 text-left">
                <h3 class="text-xl font-semibold text-green-600 mb-4">Ingediende</h3>
                @if ($submitted->count())
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach ($submitted as $report)
                            <a href="{{ route('reports.show', $report) }}"
                                class="bg-green-100 hover:bg-green-200 rounded-lg p-4 text-gray-800 block shadow-sm">
                                <div class="font-medium">{{ $report->title }}</div>
                                <div class="text-xs text-gray-600 mt-1">
                                    {{ $report->created_at->format('d-m-Y H:i') }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Nog geen rapportages ingediend.</p>
                @endif
            </div>

            {{-- Gearchiveerde --}}
            <div class="mb-10 text-left">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Gearchiveerde</h3>
                @if ($archived->count())
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach ($archived as $report)
                            <a href="{{ route('reports.show', $report) }}"
                                class="bg-gray-200 hover:bg-gray-300 rounded-lg p-4 text-gray-800 block shadow-sm">
                                <div class="font-medium">{{ $report->title }}</div>
                                <div class="text-xs text-gray-600 mt-1">
                                    {{ $report->created_at->format('d-m-Y H:i') }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Geen gearchiveerde rapportages.</p>
                @endif
            </div>

            {{-- Knop nieuwe rapportage --}}
            <a href="{{ route('reports.create') }}"
                class="inline-block bg-green-500 hover:bg-green-600 text-white rounded-lg font-bold px-4 py-3">
                Rapportage aanmaken
            </a>
        </div>
    </main>
</body>

</html>

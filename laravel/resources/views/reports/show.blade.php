<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $report->title }}</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen">
    <header class="bg-sky-500 p-4 drop-shadow-md text-white">
        <a href="{{ route('reports.index') }}" class="underline">← Terug naar overzicht</a>
    </header>

    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow p-6 mt-10">
        {{-- resources/views/reports/show.blade.php (bovenin de card) --}}
        <div class="flex gap-2 mt-4">
            <a href="{{ route('reports.edit', $report) }}"
                class="px-3 py-1 rounded bg-blue-600 hover:bg-blue-700 text-white text-sm">Bewerken</a>
            <form method="POST" action="{{ route('reports.destroy', $report) }}"
                onsubmit="return confirm('Verwijderen?');">
                @csrf @method('DELETE')
                <button class="px-3 py-1 rounded bg-red-600 hover:bg-red-700 text-white text-sm">Verwijderen</button>
            </form>
        </div>

        <h1 class="text-2xl font-bold">{{ $report->title }}</h1>
        <p class="text-sm text-gray-500 mt-1">
            Aangemaakt: {{ $report->created_at->format('d-m-Y H:i') }} • Status: {{ ucfirst($report->status) }}
        </p>

        @if ($report->description)
            <div class="prose max-w-none mt-6">{{ $report->description }}</div>
        @else
            <p class="text-gray-500 mt-6">Geen beschrijving.</p>
        @endif
    </div>
</body>

</html>

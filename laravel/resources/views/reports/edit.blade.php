{{-- resources/views/reports/edit.blade.php --}}
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $report->title }} – Bewerken</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen">
    <header class="bg-sky-500 p-4 drop-shadow-md">
        <a href="{{ route('reports.index') }}" class="text-white underline">← Terug</a>
    </header>

    <div class="max-w-xl mx-auto bg-white rounded-lg shadow p-6 mt-10">
        <h1 class="text-xl font-semibold mb-4">Rapportage bewerken</h1>
        <form method="POST" action="{{ route('reports.update', $report) }}" class="space-y-4">
            @method('PUT')
            @include('reports._form', ['report' => $report])
            <div class="flex gap-3 pt-6">
                <a href="{{ route('reports.index') }}"
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium transition">
                    Annuleren
                </a>
                <button
                    class="px-4 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white font-semibold shadow-sm transition">
                    Opslaan
                </button>
            </div>

    </div>
</body>

</html>

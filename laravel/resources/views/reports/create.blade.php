<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuwe rapportage</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen">
    <header class="bg-sky-500 p-4 drop-shadow-md">
        <a href="{{ route('reports.index') }}" class="text-white underline">← Terug</a>
    </header>

    <div class="max-w-xl mx-auto bg-white rounded-lg shadow p-6 mt-10">
        <h1 class="text-xl font-semibold mb-4">Nieuwe rapportage</h1>

        <form method="POST" action="{{ route('reports.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Titel <span class="text-red-500">*</span></label>
                <input name="title" value="{{ old('title') }}" required
                    class="w-full rounded border-gray-300 focus:ring-sky-500 focus:border-sky-500">
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Beschrijving</label>
                <textarea name="description" rows="5"
                    class="w-full rounded border-gray-300 focus:ring-sky-500 focus:border-sky-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <a href="{{ route('reports.index') }}"
                    class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Annuleren</a>
                <button class="px-4 py-2 rounded bg-green-500 hover:bg-green-600 text-white font-semibold">
                    Opslaan
                </button>
            </div>
        </form>
    </div>
</body>

</html>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beheer login</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50 text-gray-900">

    @include('header')

    <main class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-2xl shadow p-6">
            <h1 class="text-2xl font-semibold mb-1">Beheer toegang</h1>
            <p class="text-gray-600 mb-6">Voer de beheercode in om verder te gaan.</p>

            @if (session('info'))
                <div class="mb-4 rounded-lg border border-sky-200 bg-sky-50 px-4 py-2 text-sky-800">
                    {{ session('info') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-red-800">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.verify') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="pin">Beheercode</label>
                    <input id="pin" name="pin" type="password" inputmode="numeric" autocomplete="off"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-200 px-3 py-2"
                        placeholder="••••" value="{{ old('pin') }}">
                </div>

                <div class="flex items-center justify-between gap-2">
                    <a href="{{ route('reports.index') }}" class="text-gray-600 hover:text-gray-900">← Terug naar
                        overzicht</a>
                    <button type="submit"
                        class="inline-flex items-center rounded-lg bg-sky-600 text-white px-4 py-2 font-medium hover:bg-sky-700">
                        Doorgaan
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>

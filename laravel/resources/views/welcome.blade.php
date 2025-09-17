<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koninklijke Marine – Rapportages</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
    <!-- Header -->
    @include('components.header')

    <!-- Main Content -->
    <main class="flex-grow flex flex-col items-center justify-center bg-blue-100">
        <div class="bg-white shadow-md rounded-lg p-10 text-center w-full max-w-3xl">
            @if (session('success'))
                <div class="mb-4 rounded bg-green-100 text-green-800 px-3 py-2 text-sm">{{ session('success') }}</div>
            @endif

            <h2 class="text-2xl font-bold mb-2">Welkom</h2>
            <p class="text-gray-600 mb-8">Kies hieronder welke rapportage je wilt openen.</p>

            {{-- Dynamische grid --}}
            @if ($reports->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-6">
                    @foreach ($reports as $report)
                        <a href="{{ route('reports.show', $report) }}"
                            class="bg-gray-200 hover:bg-gray-300 rounded-lg py-6 text-gray-800 font-medium block">
                            {{ $report->title }}
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $report->created_at->format('d-m-Y H:i') }}
                                @if ($report->status !== 'draft')
                                    • {{ ucfirst($report->status) }}
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-2">{{ $reports->links() }}</div>
            @else
                <p class="text-gray-500 mb-6">Nog geen rapportages aangemaakt.</p>
            @endif

            <a href="{{ route('reports.create') }}"
                class="inline-block bg-green-500 hover:bg-green-600 text-white rounded-lg font-bold px-4 py-3">
                Rapportage aanmaken
            </a>
        </div>
    </main>
</body>

</html>

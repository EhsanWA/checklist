<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $report->title }}</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen">

    {{-- <header class="bg-sky-500 p-4 drop-shadow-md text-white">
        <a href="{{ route('reports.index') }}" class="underline">← Terug naar overzicht</a>
    </header> --}}
    @include('tabblad')

</body>

</html>

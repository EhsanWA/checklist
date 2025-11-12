<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #0f172a;
            margin: 24px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 4px;
        }

        h2 {
            font-size: 16px;
            margin-top: 24px;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 8px;
            vertical-align: top;
        }

        .meta {
            margin-bottom: 16px;
        }

        .muted {
            color: #6b7280;
        }

        .signature {
            margin-top: 32px;
        }

        .signature img {
            max-width: 320px;
            border: 1px solid #e5e7eb;
            padding: 8px;
        }
    </style>
</head>

<body>
    <h1>Rapportage {{ $report->schip_naam ?? 'Rapport #' . $report->id }}</h1>

    <div class="meta">
        <p><strong>Schipnummer:</strong> {{ $report->schip_nummer ?? 'n.v.t.' }}</p>
        <p><strong>Monteur:</strong> {{ $report->monteur ?? 'Onbekend' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($report->status ?? 'concept') }}</p>
        <p><strong>Verzonden op:</strong> {{ optional($report->submitted_at)->format('d-m-Y H:i') ?? now()->format('d-m-Y H:i') }}</p>
    </div>

    @php
        $completedChecks = collect($groupedChecks['gecontroleerd'] ?? []);
        if (!empty($groupedChecks['bijzonderheden'])) {
            $completedChecks = $completedChecks->merge($groupedChecks['bijzonderheden']);
        }
    @endphp

    @if ($completedChecks->isNotEmpty())
        <h2>Gecontroleerd</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 25%;">Categorie</th>
                    <th style="width: 45%;">Controle</th>
                    <th style="width: 20%;">Code</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($completedChecks as $check)
                    <tr>
                        <td style="text-align: center;">
                            {{ ($check['status'] ?? 'gecontroleerd') === 'bijzonderheden' ? 'X' : 'V' }}
                        </td>
                        <td>{{ $check['category'] }}</td>
                        <td>{{ $check['label'] }}</td>
                        <td class="muted">{{ $check['code'] ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if (!empty($groupedChecks['bijzonderheden']))
        <h2>Bijzonderheden</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 25%;">Categorie</th>
                    <th style="width: 45%;">Controle</th>
                    <th style="width: 30%;">Opmerkingen & foto's</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groupedChecks['bijzonderheden'] as $check)
                    <tr>
                        <td>{{ $check['category'] }}</td>
                        <td>
                            <div>{{ $check['label'] }}</div>
                            <div class="muted">{{ $check['code'] ?? '' }}</div>
                        </td>
                        <td>
                            <div>{{ $check['notes'] ?: 'Geen toelichting toegevoegd.' }}</div>
                            @if (!empty($check['photos']))
                                <div class="muted" style="margin-top: 4px;">
                                    Foto's:
                                    @foreach ($check['photos'] as $photo)
                                        <div>- {{ basename($photo) }}</div>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if ($signatureImage)
        <div class="signature">
            <h2>Handtekening</h2>
            <img src="{{ $signatureImage }}" alt="Handtekening">
        </div>
    @endif
</body>

</html>

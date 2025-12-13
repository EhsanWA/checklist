<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h2>Nieuwe rapportage ingediend</h2>

    <p>Er is een nieuwe rapportage ingediend.</p>

    <ul>
        <li><strong>Rapport ID:</strong> {{ $report->id }}</li>
        <li><strong>Datum:</strong> {{ $report->submitted_at }}</li>
    </ul>

    <p>De PDF is als bijlage toegevoegd.</p>
</body>
</html>

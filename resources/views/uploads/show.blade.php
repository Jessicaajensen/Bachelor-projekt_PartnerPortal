<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <title>Forhåndsvisning af upload</title>
</head>
<body>
    <h1>Forhåndsvisning: {{ $upload->filename }}</h1>

    <p><a href="{{ route('uploads.create') }}">Upload en ny fil</a> | <a href="/">Forsiden</a></p>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <ul>
        <li>Skilletegn fundet: {{ $csv['delimiter'] === ';' ? 'semikolon' : 'komma' }}</li>
        <li>Kolonner fundet i filen: {{ implode(', ', $csv['headers']) }}</li>
    </ul>

    @if (count($missing) > 0)
        <h2 style="color: red;">Filen kan ikke bruges endnu</h2>
        <p>Filen mangler disse kolonner:</p>
        <ul>
            @foreach ($missing as $field => $label)
                <li>{{ $label }} (kolonnen skal hedde {{ $field }})</li>
            @endforeach
        </ul>
        <p>Ret kolonnenavnene i filen, og upload den igen.</p>
    @else
        <h2>Oversigt</h2>
        <p>
            {{ $counts['klar'] }} rækker er klar,
            {{ $counts['fejl'] }} har fejl,
            {{ $counts['advarsler'] }} har advarsler.
        </p>

        <p>
            @foreach (['alle' => 'Alle (' . count($csv['rows']) . ')', 'fejl' => 'Fejl (' . $counts['fejl'] . ')', 'advarsler' => 'Advarsler (' . $counts['advarsler'] . ')', 'klar' => 'Klar (' . $counts['klar'] . ')'] as $key => $label)
                @if ($tab === $key)
                    <strong>[{{ $label }}]</strong>
                @else
                    <a href="{{ route('uploads.show', ['id' => $upload->id, 'fane' => $key]) }}">{{ $label }}</a>
                @endif
            @endforeach
        </p>

        @if ($tab === 'fejl')
            <p>Ret værdierne i de røde felter, og tryk på Gem rettelser. Alle rettelser gemmes på én gang.</p>
        @endif

        <form method="POST" action="{{ route('uploads.update', $upload->id) }}">
            @csrf
            @method('PUT')

            <table border="1" cellpadding="6">
                <thead>
                    <tr>
                        <th>Række</th>
                        <th>Status</th>
                        @foreach ($csv['headers'] as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($visible as $index)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ ['fejl' => 'Fejl', 'advarsler' => 'Advarsel', 'klar' => 'Klar'][$status[$index]] }}</td>
                            @foreach ($csv['headers'] as $header)
                                @php
                                    $cellIssues = collect($issues[$index])->where('field', $header);
                                    $hasError = $cellIssues->contains('level', 'error');
                                    $color = $hasError ? '#fdd' : ($cellIssues->isNotEmpty() ? '#ffd' : 'transparent');
                                @endphp
                                <td style="background: {{ $color }};">
                                    @if ($tab === 'fejl' && $hasError)
                                        <input type="text" name="rows[{{ $rowIds[$index] }}][{{ $header }}]" value="{{ $csv['rows'][$index][$header] }}">
                                    @else
                                        {{ $csv['rows'][$index][$header] }}
                                    @endif
                                    @foreach ($cellIssues as $issue)
                                        <div><small>{{ $issue['message'] }}. {{ $issue['suggestion'] }}</small></div>
                                    @endforeach
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($tab === 'fejl' && count($visible) > 0)
                <p><button type="submit">Gem rettelser</button></p>
            @endif
        </form>
    @endif
</body>
</html>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <title>Forhåndsvisning af upload</title>
</head>
<body>
    <h1>Forhåndsvisning: {{ $upload->filename }}</h1>

    <p><a href="{{ route('uploads.create') }}">Upload en ny fil</a> | <a href="/">Forsiden</a></p>

    <ul>
        <li>Skilletegn fundet: {{ $csv['delimiter'] === ';' ? 'semikolon' : 'komma' }}</li>
        <li>Kolonner: {{ count($csv['headers']) }}</li>
        <li>Rækker: {{ count($csv['rows']) }}</li>
        <li>Rækker med fejl: {{ collect($issues)->filter(fn ($list) => count($list) > 0)->count() }}</li>
    </ul>

    <h2>Første rækker</h2>

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Række</th>
                @foreach ($csv['headers'] as $header)
                    <th>{{ $header }}</th>
                @endforeach
                <th>Resultat</th>
            </tr>
        </thead>
        <tbody>
            @foreach (array_slice($csv['rows'], 0, 10, true) as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    @foreach ($csv['headers'] as $header)
                        <td>{{ $row[$header] }}</td>
                    @endforeach
                    <td>
                        @forelse ($issues[$index] as $issue)
                            <div>{{ $issue['level'] === 'error' ? 'Fejl' : 'Advarsel' }}: {{ $issue['message'] }}. {{ $issue['suggestion'] }}</div>
                        @empty
                            Klar
                        @endforelse
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
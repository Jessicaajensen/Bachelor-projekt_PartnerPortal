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
    </ul>

    <h2>Første rækker</h2>

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                @foreach ($csv['headers'] as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach (array_slice($csv['rows'], 0, 10) as $row)
                <tr>
                    @foreach ($csv['headers'] as $header)
                        <td>{{ $row[$header] }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <title>Upload produktfil</title>
</head>
<body>
    <h1>Upload produktfil for {{ auth()->user()->partner->name }}</h1>

    <p><a href="/">Tilbage til forsiden</a></p>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('uploads.store') }}" enctype="multipart/form-data">
        @csrf

        <label for="file">Vælg en CSV-fil:</label><br>
        <input type="file" id="file" name="file" accept=".csv,.txt">

        @error('file')
            <p style="color: red;">{{ $message }}</p>
        @enderror

        <p><button type="submit">Upload</button></p>
    </form>
</body>
</html>
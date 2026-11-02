<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <title>Forside</title>
</head>
<body>
    <h1>Velkommen, {{ auth()->user()->name }}</h1>
    <p>Partner: {{ auth()->user()->partner->name }}</p>

    <form method="POST" action="/logout">
        @csrf
        <button type="submit">Log ud</button>
    </form>
</body>
</html>
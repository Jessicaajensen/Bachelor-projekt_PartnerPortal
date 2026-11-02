<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <title>Log ind</title>
</head>
<body>
    <h1>Log ind</h1>

    <form method="POST" action="/login">
        @csrf

        <div>
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password">Adgangskode</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit">Log ind</button>
    </form>
</body>
</html>
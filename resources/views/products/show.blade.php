<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }}</title>
</head>
<body>
    <h1>{{ $product->name }}</h1>

    <p><a href="{{ route('products.index') }}">Tilbage til produktlisten</a></p>

    <ul>
        <li>SKU: {{ $product->sku }}</li>
        <li>EAN: {{ $product->ean }}</li>
        <li>Vejl. pris: {{ number_format($product->recommended_price, 2, ',', '.') }} kr.</li>
    </ul>
</body>
</html>
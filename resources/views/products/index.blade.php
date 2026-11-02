<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <title>Produkter</title>
</head>
<body>
    <h1>Produkter for {{ auth()->user()->partner->name }}</h1>

    <p><a href="/">Tilbage til forsiden</a></p>

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Navn</th>
                <th>EAN</th>
                <th>Vejl. pris</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->ean }}</td>
                    <td>{{ number_format($product->recommended_price, 2, ',', '.') }} kr.</td>
                    <td><a href="{{ route('products.show', $product->id) }}">Vis</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
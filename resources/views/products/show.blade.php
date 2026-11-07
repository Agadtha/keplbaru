<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Product</title>
</head>
<body>

    <h1>Detail Product</h1>

    <div>
        <p>
            <strong>Nama:</strong>
            {{ $product->name }}
        </p>

        <p>
            <strong>Harga:</strong>
            Rp {{ number_format($product->price, 0, ',', '.') }}
        </p>

        <p>
            <strong>Deskripsi:</strong>
            {{ $product->description ?: 'Tidak ada deskripsi.' }}
        </p>

        <p>
            <strong>Dibuat:</strong>
            {{ $product->created_at->format('d M Y H:i') }}
        </p>

        <p>
            <strong>Terakhir diperbarui:</strong>
            {{ $product->updated_at->format('d M Y H:i') }}
        </p>
    </div>

    <br>

    <a href="{{ route('products.index') }}">
        Kembali ke Products
    </a>

    <a href="{{ route('products.edit', $product) }}">
        Edit Product
    </a>

</body>
</html>

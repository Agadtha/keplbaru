<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
</head>
<body>

    <h1>Edit Product</h1>

    @if ($errors->any())
        <div>
            <strong>Terjadi kesalahan:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="name">Nama Product</label>
            <br>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $product->name) }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="price">Harga</label>
            <br>
            <input
                type="number"
                id="price"
                name="price"
                value="{{ old('price', $product->price) }}"
                min="0"
                step="0.01"
                required
            >
        </div>

        <br>

        <div>
            <label for="description">Deskripsi</label>
            <br>
            <textarea
                id="description"
                name="description"
                rows="5"
                cols="40"
            >{{ old('description', $product->description) }}</textarea>
        </div>

        <br>

        <button type="submit">Update</button>

        <a href="{{ route('products.index') }}">Batal</a>
    </form>

</body>
</html>
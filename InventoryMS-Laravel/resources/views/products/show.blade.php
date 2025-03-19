
    <h1>Detail Produk</h1>
    <p>Nama Produk: {{ $product->name }}</p>
    <p>Harga: Rp.{{ number_format($product->price, 0) }}</p>
    <p>Stock: {{ $product->stock }}</p>
    <p>Kategori: {{ $product->category->name }}</p>
    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" width="200">



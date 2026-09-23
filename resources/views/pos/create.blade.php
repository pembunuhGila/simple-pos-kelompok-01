@extends('layouts.app')

@section('title', 'Kasir')

@section('content')

<h1 class="text-lg font-semibold mb-4">Transaksi Kasir</h1>

<div x-data="{
    cart: [],
    selectedProductId: null,

```
addToCart(id, name, price) {
    this.cart.push({ id, name, price });
},

removeFromCart(index) {
    this.cart.splice(index, 1);
},

subtotal() {
    return this.cart.reduce((sum, item) => sum + item.price, 0);
}
```

}">

```
<!-- Grid Produk -->
<div class="grid grid-cols-3 gap-4">
    @foreach ($products as $product)
    <div
        class="border rounded-md p-3 cursor-pointer"
        :class="{ 'ring-2 ring-blue-500': selectedProductId === {{ $product->id }} }"
        @click="selectedProductId = {{ $product->id }}; addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})"
    >
        <p class="font-medium">{{ $product->name }}</p>

        <p class="text-sm text-slate-500">
            Rp {{ number_format($product->price) }}
        </p>

        <!-- Badge Stok Menipis -->
        @if ($product->stock < 10)
            <span class="bg-amber-100 text-amber-700 text-xs px-2 py-1 rounded">
                Stok Menipis
            </span>
        @endif
    </div>
    @endforeach
</div>

<!-- Ringkasan Keranjang -->
<div class="mt-4 border-t pt-3">

    <template x-for="(item, index) in cart" :key="index">
        <div class="flex justify-between items-center mb-2">

            <p x-text="item.name + ' - Rp ' + item.price"></p>

            <!-- Tombol Hapus -->
            <button
                @click="removeFromCart(index)"
                class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600"
            >
                Hapus
            </button>

        </div>
    </template>

    <!-- Subtotal -->
    <p class="font-semibold mt-2">
        Subtotal: Rp <span x-text="subtotal()"></span>
    </p>

</div>
```

</div>

@endsection

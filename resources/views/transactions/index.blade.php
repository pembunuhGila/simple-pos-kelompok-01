@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<h1 class="text-lg font-semibold mb-4">Riwayat Transaksi</h1>

@foreach ($transactions as $transaction)
<div class="border rounded-md p-3 mb-3">
    <p class="font-medium">
        Transaksi #{{ $transaction->id }}
        &middot; {{ $transaction->created_at?->format('d M Y H:i') }}
        &middot; Rp {{ number_format($transaction->total ?? 0) }}
    </p>

    <ul class="text-sm text-slate-500 mt-1">
        @forelse ($transaction->details ?? [] as $detail)
            <li>
                {{ $detail->product?->name ?? 'Produk tidak ditemukan' }} 
                &times; {{ $detail->qty }} 
                = Rp {{ number_format($detail->subtotal ?? 0) }}
            </li>
        @empty
            <li class="italic text-gray-400">Belum ada detail barang untuk transaksi ini.</li>
        @endforelse
    </ul>
</div>
@endforeach

<div class="mt-4">
    {{ $transactions->links() }}
</div>
@endsection
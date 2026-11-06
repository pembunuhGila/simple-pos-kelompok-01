@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
<div class="max-w-4xl mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold text-slate-800">Daftar Kategori</h1>
        <a href="{{ route('categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-md font-medium">
            + Tambah Kategori
        </a>
    </div>

    @if (session('status') || session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md mb-4 text-sm">
            {{ session('status') ?? session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-4 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Search & Filter -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-slate-200 mb-4">
        <form method="GET" action="{{ route('categories.index') }}" class="flex gap-2">
            <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama kategori..."
                   class="flex-1 border border-slate-300 rounded-md px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500 focus:outline-none">
            <input type="hidden" name="sort" value="{{ $sort }}">
            <input type="hidden" name="direction" value="{{ $direction }}">
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-md text-sm">
                Cari
            </button>
            @if ($q)
                <a href="{{ route('categories.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-3 py-2 rounded-md text-sm flex items-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase text-xs">
                <tr>
                    <th class="py-3 px-4">
                        <a href="{{ route('categories.index', ['sort' => 'name', 'direction' => ($sort === 'name' && $direction === 'asc') ? 'desc' : 'asc', 'q' => $q]) }}" class="hover:underline flex items-center gap-1">
                            Nama Kategori
                            @if ($sort === 'name')
                                <span>{{ $direction === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="py-3 px-4">
                        <a href="{{ route('categories.index', ['sort' => 'products_count', 'direction' => ($sort === 'products_count' && $direction === 'asc') ? 'desc' : 'asc', 'q' => $q]) }}" class="hover:underline flex items-center gap-1">
                            Jumlah Produk
                            @if ($sort === 'products_count')
                                <span>{{ $direction === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="py-3 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($categories as $category)
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 font-medium text-slate-800">
                            {{ $category->name }}
                        </td>
                        <td class="py-3 px-4 text-slate-600">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->products_count > 0 ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $category->products_count }} produk
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right space-x-2">
                            <a href="{{ route('categories.edit', $category) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium ml-2">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-8 text-center text-slate-400">
                            Tidak ada kategori ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection

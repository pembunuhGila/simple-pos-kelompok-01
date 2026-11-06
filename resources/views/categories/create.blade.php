@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="max-w-md mx-auto p-4">
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-bold text-slate-800">Tambah Kategori</h1>
        <a href="{{ route('categories.index') }}" class="text-sm text-slate-600 hover:underline">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Kategori</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                       placeholder="Contoh: Makanan, Minuman, Snack"
                       class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500 focus:outline-none @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Simpan Kategori
                </button>
                <a href="{{ route('categories.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-md text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Import Produk')

@section('content')
    <h1 class="text-lg font-semibold mb-4">
        Import Produk dari CSV
    </h1>

    @if (session('success'))
        <div class="bg-green-50 text-green-700 p-3 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST"
          action="{{ route('import.store') }}"
          enctype="multipart/form-data"
          class="max-w-md mb-8">
        @csrf

        <label class="block mb-3">
            <span class="text-sm font-medium">File CSV</span>

            <input type="file"
                   name="csv"
                   accept=".csv"
                   class="mt-1 block w-full text-sm">

            @error('csv')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </label>

        <p class="text-xs text-slate-500 mb-3">
            Kolom yang dibutuhkan: name, category_id, price.
        </p>

        <button type="submit"
                class="bg-slate-900 text-white px-4 py-2 rounded-md text-sm">
            Import
        </button>
    </form>
    <h2 class="text-lg font-semibold mt-8 mb-3">
        Daftar Baris Import yang Gagal
    </h2>

    <div class="bg-white rounded-lg shadow overflow-hidden max-w-4xl">
        <table class="w-full border-collapse border border-slate-200 text-sm">
            <thead>
                <tr class="bg-slate-100 text-left">
                    <th class="border border-slate-200 p-2">Nomor Baris</th>
                    <th class="border border-slate-200 p-2">File</th>
                    <th class="border border-slate-200 p-2">Pesan Error</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($importErrors ?? [] as $error)
                    <tr>
                        <td class="border border-slate-200 p-2 text-center">{{ $error->row_number }}</td>
                        <td class="border border-slate-200 p-2">{{ $error->file_path }}</td>
                        <td class="border border-slate-200 p-2 text-red-600">{{ $error->error_message }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="border border-slate-200 p-4 text-center text-slate-500">
                            Belum ada data import yang gagal.
                        </td>
                    </tr>
    <h2 class="text-lg font-semibold mt-8 mb-3">
    Import Menggunakan Laravel Excel
</h2>

<form method="POST"
      action="{{ route('import.excel') }}"
      enctype="multipart/form-data"
      class="max-w-md">
    @csrf

    <label class="block mb-3">
        <span class="text-sm font-medium">File CSV / Excel (.xlsx, .xls)</span>
        <input type="file"
               name="csv"
               accept=".csv,.xlsx,.xls"
               class="mt-1 block w-full text-sm">
    </label>

    <button type="submit"
            class="bg-emerald-700 text-white px-4 py-2 rounded-md text-sm">
        Import Laravel Excel
    </button>
</form>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
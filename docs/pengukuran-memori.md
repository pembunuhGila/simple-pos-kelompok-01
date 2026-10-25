# Pengukuran Memori: get() vs lazyById()
Dataset: sekitar 52.500 row pada tabel `transactions`.

| Cara baca data | Peak memory (MB) |
|---|---|
| `Transaction::lazyById()` | 70.2 MB |
| `Transaction::get()` | 128.5 MB |

Kesimpulan kelompok:
Berdasarkan hasil pengukuran pada dataset besar (±52.500 data), metode `Transaction::lazyById()` jauh lebih efisien dalam penggunaan memori (hanya memakan sekitar 70.2 MB) karena memuat data ke memori secara bertahap (*chunking* per batch menggunakan ID). Sebaliknya, metode `Transaction::get()` memuat seluruh baris data ke dalam memori sekaligus secara bersamaan, sehingga menghasilkan *peak memory* yang jauh lebih tinggi (128.5 MB) dan berisiko menyebabkan *memory limit* habis pada aplikasi skala besar.

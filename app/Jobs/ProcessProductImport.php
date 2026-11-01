<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\ImportError;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProcessProductImport implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public array $backoff = [10, 60];
    public int $timeout = 60;

    public function __construct(private string $csvPath) {}

    public function handle(): void
    {
        $path = Storage::path($this->csvPath);

        if (!file_exists($path)) {
            return;
        }

        // Membaca file CSV baris per baris
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $rows = array_map('str_getcsv', $lines);
        
        if (empty($rows)) {
            return;
        }

        $header = array_shift($rows);

        foreach ($rows as $index => $row) {
            // Baris 1 adalah header, data dimulai dari baris ke-2
            $rowNumber = $index + 2; 

            // 1. Cek kecocokan jumlah kolom
            if (count($row) !== count($header)) {
                ImportError::create([
                    'file_path' => $this->csvPath,
                    'row_number' => $rowNumber,
                    'error_message' => 'Jumlah kolom tidak sesuai dengan header CSV.',
                ]);
                continue; // Lanjutkan ke baris berikutnya, jangan gagalkan job
            }

            $combinedData = array_combine($header, $row);

            // 2. Validasi per baris
            $validator = Validator::make($combinedData, [
                'name' => ['required', 'string'],
                'category_id' => ['required', 'exists:categories,id'],
                'price' => ['required', 'integer', 'min:0'],
            ]);

            // 3. Jika validasi gagal -> Simpan ke tabel import_errors
            if ($validator->fails()) {
                ImportError::create([
                    'file_path' => $this->csvPath,
                    'row_number' => $rowNumber,
                    'error_message' => implode('; ', $validator->errors()->all()),
                ]);
                continue; // Lanjutkan ke baris berikutnya, jangan gagalkan job
            }

            // 4. Jika validasi sukses -> Simpan / Perbarui Produk
            $data = $validator->validated();
            Product::updateOrCreate(
                [
                    'name' => $data['name'], 
                    'category_id' => $data['category_id']
                ],
                [
                    'price' => $data['price']
                ]
            );
        }

        // Hapus file CSV dari storage setelah selesai diproses
        Storage::delete($this->csvPath);
    }
}
<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ProductsImport implements
    ToModel,
    WithHeadingRow,
    WithChunkReading
{
    public function model(array $row)
    {
        $validator = Validator::make($row, [
            'name' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return null;
        }

        return Product::updateOrCreate(
            [
                'name' => $row['name'],
                'category_id' => $row['category_id'],
            ],
            [
                'price' => $row['price'],
            ]
        );
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
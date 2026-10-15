<?php

namespace App\Http\Requests;
use App\Models\Product;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ];
    }
    
public function withValidator(Validator $validator): void
{
    $validator->after(function (Validator $validator) {
        $items = $this->input('items', []);

        if (! is_array($items)) {
            return;
        }

        $quantities = [];

        foreach ($items as $item) {
            if (
                ! is_array($item) ||
                ! isset($item['product_id'], $item['qty']) ||
                ! is_numeric($item['product_id']) ||
                ! is_numeric($item['qty']) ||
                (int) $item['qty'] < 1
            ) {
                continue;
            }

            $productId = (int) $item['product_id'];
            $quantities[$productId] =
                ($quantities[$productId] ?? 0) + (int) $item['qty'];
        }

        foreach ($quantities as $productId => $qty) {
            $product = Product::find($productId);

            if ($product && $qty > $product->stock) {
                $validator->errors()->add(
                    'items',
                    "Stok produk {$product->name} tidak mencukupi. "
                    . "Stok tersedia: {$product->stock}, "
                    . "jumlah diminta: {$qty}."
                );
            }
        }
    });
}

}

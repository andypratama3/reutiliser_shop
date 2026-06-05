<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Product::with('category')->get()->map(function ($product) {
            return [
                $product->sku,
                $product->name,
                $product->category?->name ?? '-',
                number_format($product->price, 0, ',', '.'),
                $product->stock,
                $product->sold_count,
                $product->status,
                $product->created_at->format('d/m/Y'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Nama Produk',
            'Kategori',
            'Harga',
            'Stok',
            'Terjual',
            'Status',
            'Tanggal Dibuat',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

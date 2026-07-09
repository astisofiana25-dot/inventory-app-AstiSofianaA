<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        return $this->products;
    }

    public function headings(): array
    {
        return ['ID', 'Kode Barang', 'Nama Barang', 'Kategori', 'Stok', 'Lokasi', 'Kondisi', 'Status'];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->kode_barang,
            $product->nama_barang,
            $product->category?->name ?? '-',
            $product->stok,
            $product->lokasi_penyimpanan ?? '-',
            ucfirst(str_replace('_', ' ', $product->kondisi_barang ?? '-')),
            ucfirst($product->status ?? '-'),
        ];
    }
}

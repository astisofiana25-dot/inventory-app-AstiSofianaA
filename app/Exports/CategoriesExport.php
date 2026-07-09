<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoriesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $categories;

    public function __construct($categories)
    {
        $this->categories = $categories;
    }

    public function collection()
    {
        return $this->categories;
    }

    public function headings(): array
    {
        return ['ID', 'Kode Kategori', 'Nama Kategori', 'Jumlah Produk'];
    }

    public function map($category): array
    {
        return [
            $category->id,
            sprintf('KTG-%03d', $category->id),
            $category->name,
            $category->products_count ?? 0,
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $elektronik = Category::where('name', 'Elektronik')->first();
        $furnitur = Category::where('name', 'Furnitur')->first();
        $atk = Category::where('name', 'Alat Tulis Kantor')->first();

        $products = [
            ['kode_barang' => 'BRG-001', 'nama_barang' => 'Laptop Dell Latitude', 'category_id' => $elektronik->id, 'stok' => 10, 'lokasi_penyimpanan' => 'Gudang IT Lt.2', 'kondisi_barang' => 'baik'],
            ['kode_barang' => 'BRG-002', 'nama_barang' => 'Proyektor Epson', 'category_id' => $elektronik->id, 'stok' => 4, 'lokasi_penyimpanan' => 'Ruang AV', 'kondisi_barang' => 'baik'],
            ['kode_barang' => 'BRG-003', 'nama_barang' => 'Kursi Kantor Ergonomis', 'category_id' => $furnitur->id, 'stok' => 25, 'lokasi_penyimpanan' => 'Gudang Umum', 'kondisi_barang' => 'baik'],
            ['kode_barang' => 'BRG-004', 'nama_barang' => 'Meja Rapat Lipat', 'category_id' => $furnitur->id, 'stok' => 8, 'lokasi_penyimpanan' => 'Gudang Umum', 'kondisi_barang' => 'rusak_ringan'],
            ['kode_barang' => 'BRG-005', 'nama_barang' => 'Printer Multifungsi', 'category_id' => $atk->id, 'stok' => 6, 'lokasi_penyimpanan' => 'Ruang ATK', 'kondisi_barang' => 'baik'],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['kode_barang' => $product['kode_barang']], $product);
        }
    }
}

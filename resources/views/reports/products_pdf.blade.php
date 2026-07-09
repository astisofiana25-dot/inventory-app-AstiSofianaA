<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Produk</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111; margin: 0; padding: 20px; }
        h1 { font-size: 18px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; vertical-align: top; }
        th { background: #f0f0f0; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Laporan Produk</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Lokasi</th>
                <th>Kondisi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->kode_barang }}</td>
                    <td>{{ $product->nama_barang }}</td>
                    <td>{{ $product->category?->name ?? '-' }}</td>
                    <td>{{ $product->stok }}</td>
                    <td>{{ $product->lokasi_penyimpanan ?? '-' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $product->kondisi_barang ?? '-')) }}</td>
                    <td>{{ ucfirst($product->status ?? '-') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

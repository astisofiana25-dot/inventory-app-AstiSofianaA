<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #111;
            margin: 0;
            padding: 20px;
        }
        h1 {
            font-size: 18px;
            margin-bottom: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th,
        td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background: #f0f0f0;
            font-weight: bold;
        }
        .table-wrapper {
            width: 100%;
        }
    </style>
</head>
<body>
    <h1>Laporan Peminjaman</h1>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Peminjam</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th>Items</th>
                    <th>Keterangan</th>
                    <th>Gambar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($borrowings as $b)
                @php
                    $isLate = $b->tanggal_kembali && $b->tanggal_kembali->isPast() && $b->status !== 'dikembalikan';
                    $imageDetail = $b->details->first(fn($d) => !empty($d->product?->image));
                    $imagePath = $imageDetail && $imageDetail->product->image ? storage_path('app/public/' . $imageDetail->product->image) : null;
                    $imageSrc = null;
                    if ($imagePath && file_exists($imagePath)) {
                        $mime = mime_content_type($imagePath) ?: 'image/jpeg';
                        $imageSrc = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($imagePath));
                    }
                @endphp
                <tr style="background-color: {{ $isLate ? '#ffe6e6' : 'transparent' }};">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $b->nama_peminjam }}</td>
                    <td>{{ optional($b->tanggal_pinjam)->format('Y-m-d') }}</td>
                    <td>{{ optional($b->tanggal_kembali)->format('Y-m-d') }}</td>
                    <td>{{ ucfirst($b->status) }}</td>
                    <td>{{ $b->details->map(fn($d) => $d->product->nama_barang . ' x' . $d->jumlah)->join(', ') }}</td>
                    <td>{{ $b->keterangan }}</td>
                    <td style="text-align:center;">
                        @if($imageSrc)
                            <img src="{{ $imageSrc }}" alt="Gambar {{ $imageDetail->product->nama_barang }}" style="width:80px; height:80px; object-fit:cover; border:1px solid #ccc; border-radius:6px;" />
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>

<?php

namespace App\Exports;

use App\Models\Borrowing;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BorrowingsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $from;
    protected $to;
    protected $status;
    protected $search;
    protected $productId;

    public function __construct($from = null, $to = null, $status = null, $search = null, $productId = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->status = $status;
        $this->search = $search;
        $this->productId = $productId;
    }

    public function collection()
    {
        $query = Borrowing::with('details.product')->orderBy('tanggal_pinjam');

        // apply status and search filters
        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->search) {
            $term = "%{$this->search}%";
            $query->where(function ($q) use ($term) {
                $q->where('nama_peminjam', 'like', $term)
                  ->orWhereHas('processedBy', function ($q2) use ($term) {
                      $q2->where('name', 'like', $term)->orWhere('email', 'like', $term);
                  });
            });
        }

        // product filter
        if ($this->productId) {
            $query->whereHas('details', function ($qd) {
                $qd->where('product_id', $this->productId);
            });
        }

        // apply inclusive date filters using Carbon for both borrow and return dates
        if ($this->from || $this->to) {
            try {
                $from = $this->from ? Carbon::parse($this->from)->startOfDay() : null;
                $to = $this->to ? Carbon::parse($this->to)->endOfDay() : null;

                if ($from && $to) {
                    $query->whereBetween('tanggal_pinjam', [$from, $to])
                          ->whereBetween('tanggal_kembali', [$from, $to]);
                } elseif ($from) {
                    $query->where('tanggal_pinjam', '>=', $from)
                          ->where('tanggal_kembali', '>=', $from);
                } elseif ($to) {
                    $query->where('tanggal_pinjam', '<=', $to)
                          ->where('tanggal_kembali', '<=', $to);
                }
            } catch (\Exception $e) {
                // fallback to date-only compare
                if ($this->from && $this->to) {
                    $query->whereDate('tanggal_pinjam', '>=', $this->from)
                          ->whereDate('tanggal_pinjam', '<=', $this->to)
                          ->whereDate('tanggal_kembali', '>=', $this->from)
                          ->whereDate('tanggal_kembali', '<=', $this->to);
                } elseif ($this->from) {
                    $query->whereDate('tanggal_pinjam', '>=', $this->from)
                          ->whereDate('tanggal_kembali', '>=', $this->from);
                } elseif ($this->to) {
                    $query->whereDate('tanggal_pinjam', '<=', $this->to)
                          ->whereDate('tanggal_kembali', '<=', $this->to);
                }
            }
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ['ID', 'Nama Peminjam', 'Tanggal Pinjam', 'Tanggal Kembali', 'Status', 'Items'];
    }

    public function map($borrowing): array
    {
        $items = $borrowing->details->map(fn($d) => $d->product->nama_barang . ' x' . $d->jumlah)->join(' | ');

        return [
            $borrowing->id,
            $borrowing->nama_peminjam,
            optional($borrowing->tanggal_pinjam)->format('Y-m-d'),
            optional($borrowing->tanggal_kembali)->format('Y-m-d'),
            $borrowing->status,
            $items,
        ];
    }
}

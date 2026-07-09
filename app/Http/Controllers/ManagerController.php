<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Notification;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManagerController extends Controller
{
    public function index()
    {
        $counts = [
            'available' => Product::where('stok', '>', 0)->count(),
            'borrowed' => Borrowing::whereIsBorrowedAndNotLate()->count(),
            'returned' => Borrowing::where('status', 'dikembalikan')->count(),
            'late' => Borrowing::whereIsLate()->count(),
            'active_borrowers' => Borrowing::whereIsBorrowedAndNotLate()->distinct('nama_peminjam')->count('nama_peminjam'),
        ];

        $previousMonth = now()->subMonth();
        $reportName = $previousMonth->translatedFormat('F Y');
        $reportData = ['month' => $previousMonth->format('Y-m'), 'label' => $reportName];

        if (! Notification::where('user_id', Auth::id())
            ->where('type', 'report_ready')
            ->where('data->month', $previousMonth->format('Y-m'))
            ->exists()) {
            Notification::create([
                'user_id' => Auth::id(),
                'title' => 'Laporan siap',
                'message' => "Laporan bulan {$reportName} siap diunduh.",
                'type' => 'report_ready',
                'data' => $reportData,
            ]);
        }

        return view('manager.index', compact('counts'));
    }

    public function available()
    {
        $products = Product::with('category')->where('stok', '>', 0)->orderBy('nama_barang')->paginate(20);
        return view('manager.available', compact('products'));
    }

    public function borrowed()
    {
        $borrowings = Borrowing::with('details.product')->whereIsBorrowedAndNotLate()->orderBy('tanggal_pinjam', 'desc')->paginate(20);
        return view('manager.borrowed', compact('borrowings'));
    }

    public function returned()
    {
        $borrowings = Borrowing::with('details.product')->where('status', 'dikembalikan')->orderBy('tanggal_kembali_aktual', 'desc')->paginate(20);
        return view('manager.returned', compact('borrowings'));
    }

    public function late()
    {
        $borrowings = Borrowing::with('details.product')->whereIsLate()->orderBy('tanggal_kembali')->paginate(20);
        return view('manager.late', compact('borrowings'));
    }

    public function activeBorrowers()
    {
        $active = Borrowing::select('nama_peminjam', DB::raw('COUNT(*) as total'))
            ->whereIsBorrowedAndNotLate()
            ->groupBy('nama_peminjam')
            ->orderByDesc('total')
            ->paginate(20);

        return view('manager.active', compact('active'));
    }

    // Combined borrowings page (dipinjam / dikembalikan / terlambat)
    public function borrowings(Request $request)
    {
        $query = Borrowing::with('details.product')->orderBy('tanggal_pinjam', 'desc');

        $status = $request->query('status');
        if ($status && in_array($status, ['dipinjam', 'dikembalikan', 'terlambat'])) {
            if ($status === 'terlambat') {
                $query->whereIsLate();
            } elseif ($status === 'dipinjam') {
                $query->whereIsBorrowedAndNotLate();
            } else {
                $query->where('status', $status);
            }
        }

        $borrowings = $query->paginate(25);

        return view('manager.borrowings', compact('borrowings', 'status'));
    }
}

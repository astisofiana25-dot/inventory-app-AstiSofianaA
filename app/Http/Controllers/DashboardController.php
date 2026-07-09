<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalBarang = Product::sum('stok');
        $totalProduk = Product::count();
        $totalKategori = Category::count();
        $totalUsers = User::count();
        // overall totals (will be replaced by period-filtered counts later)
        $totalBorrowings = Borrowing::count();
                $totalReturned = Borrowing::where('status', 'dikembalikan')->count();
                $totalLate = Borrowing::where(function ($q) {
                    $q->where('status', 'terlambat')
                      ->orWhere(function ($qq) {
                          $qq->where('status', 'dipinjam')
                             ->whereDate('tanggal_kembali', '<', now()->toDateString());
                      })
                      ->orWhere(function ($qq) {
                          $qq->where('status', 'dikembalikan')
                             ->whereColumn('tanggal_kembali_aktual', '>', 'tanggal_kembali');
                      });
                })->count();
                $totalDipinjam = Borrowing::where('status', 'dipinjam')->count();

        if (Auth::user() && Auth::user()->hasRole('staff')) {
            $dueSoon = Borrowing::where('processed_by', Auth::id())
                ->where('status', 'dipinjam')
                ->whereBetween('tanggal_kembali', [now()->toDateString(), now()->addDays(3)->toDateString()])
                ->get();

            foreach ($dueSoon as $borrow) {
                if (! Notification::where('user_id', Auth::id())
                    ->where('type', 'due_reminder')
                    ->where('data->borrowing_id', $borrow->id)
                    ->exists()) {
                    Notification::create([
                        'user_id' => Auth::id(),
                        'title' => 'Pengembalian hampir jatuh tempo',
                        'message' => "Pengembalian pada {$borrow->tanggal_kembali->translatedFormat('d M Y')} segera jatuh tempo.",
                        'type' => 'due_reminder',
                        'data' => ['borrowing_id' => $borrow->id, 'due_date' => $borrow->tanggal_kembali->toDateString()],
                    ]);
                }
            }
        }

        $barangDipinjam = DB::table('borrowing_details')
            ->join('borrowings', 'borrowings.id', '=', 'borrowing_details.borrowing_id')
            ->where('borrowings.status', 'dipinjam')
            ->sum('borrowing_details.jumlah');
        $barangTersedia = max($totalBarang - $barangDipinjam, 0);

        $selectedMonth = $request->input('month');
        $selectedYear = $request->input('year', now()->format('Y'));

        $query = Borrowing::query();
        if ($selectedMonth && $selectedMonth !== 'all') {
            $start = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
            $end = $start->copy()->endOfMonth();
            $query->whereBetween('tanggal_pinjam', [$start->toDateString(), $end->toDateString()]);
        } else {
            $query->whereBetween('tanggal_pinjam', [Carbon::create($selectedYear, 1, 1)->startOfYear()->toDateString(), Carbon::create($selectedYear, 12, 31)->endOfYear()->toDateString()]);
        }

        $groupByPeriod = ($selectedMonth && $selectedMonth !== 'all') ? 'day' : 'month';

        $dailyBorrowings = (clone $query)
            ->selectRaw($groupByPeriod === 'day' ? "DATE(tanggal_pinjam) as label, COUNT(*) as total" : "DATE_FORMAT(tanggal_pinjam, '%Y-%m') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderBy('label')
            ->pluck('total', 'label');

        $dailyBorrowedUnits = BorrowingDetail::join('borrowings', 'borrowings.id', '=', 'borrowing_details.borrowing_id')
            ->selectRaw($groupByPeriod === 'day' ? "DATE(borrowings.tanggal_pinjam) as label, SUM(borrowing_details.jumlah) as total" : "DATE_FORMAT(borrowings.tanggal_pinjam, '%Y-%m') as label, SUM(borrowing_details.jumlah) as total")
            ->when($selectedMonth && $selectedMonth !== 'all', function ($q) use ($selectedMonth) {
                $start = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
                $end = $start->copy()->endOfMonth();
                return $q->whereBetween('borrowings.tanggal_pinjam', [$start->toDateString(), $end->toDateString()]);
            }, function ($q) use ($selectedYear) {
                return $q->whereBetween('borrowings.tanggal_pinjam', [Carbon::create($selectedYear, 1, 1)->startOfYear()->toDateString(), Carbon::create($selectedYear, 12, 31)->endOfYear()->toDateString()]);
            })
            ->groupBy('label')
            ->orderBy('label')
            ->pluck('total', 'label');

        $dailyReturned = (clone $query)
            ->where('status', 'dikembalikan')
            ->selectRaw($groupByPeriod === 'day' ? "DATE(tanggal_pinjam) as label, COUNT(*) as total" : "DATE_FORMAT(tanggal_pinjam, '%Y-%m') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderBy('label')
            ->pluck('total', 'label');

        $dailyLate = (clone $query)
            ->where(function ($q) {
                $q->where('status', 'terlambat')
                  ->orWhere(function ($qq) {
                      $qq->where('status', 'dipinjam')
                         ->whereDate('tanggal_kembali', '<', now()->toDateString());
                  })
                  ->orWhere(function ($qq) {
                      $qq->where('status', 'dikembalikan')
                         ->whereColumn('tanggal_kembali_aktual', '>', 'tanggal_kembali');
                  });
            })
            ->selectRaw($groupByPeriod === 'day' ? "DATE(tanggal_pinjam) as label, COUNT(*) as total" : "DATE_FORMAT(tanggal_pinjam, '%Y-%m') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderBy('label')
            ->pluck('total', 'label');

        // Build category summary based on borrowings in the selected period
        $categorySummary = BorrowingDetail::join('borrowings', 'borrowings.id', '=', 'borrowing_details.borrowing_id')
            ->join('products', 'products.id', '=', 'borrowing_details.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->when($selectedMonth && $selectedMonth !== 'all', function ($q) use ($selectedMonth) {
                $start = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
                $end = $start->copy()->endOfMonth();
                return $q->whereBetween('borrowings.tanggal_pinjam', [$start->toDateString(), $end->toDateString()]);
            }, function ($q) use ($selectedYear) {
                return $q->whereBetween('borrowings.tanggal_pinjam', [Carbon::create($selectedYear, 1, 1)->startOfYear()->toDateString(), Carbon::create($selectedYear, 12, 31)->endOfYear()->toDateString()]);
            })
            ->select('categories.id', 'categories.name', DB::raw('SUM(borrowing_details.jumlah) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->get();

        $labels = [];
        $borrowingsData = [];
        $borrowedUnitsData = [];
        $returnedData = [];
        $lateData = [];

        if ($selectedMonth && $selectedMonth !== 'all') {
            $start = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
            $end = $start->copy()->endOfMonth();
            $period = $start->copy();
            while ($period->lte($end)) {
                $key = $period->format('Y-m-d');
                $labels[] = $period->translatedFormat('d M');
                $borrowingsData[] = (int) ($dailyBorrowings[$key] ?? 0);
                $borrowedUnitsData[] = (int) ($dailyBorrowedUnits[$key] ?? 0);
                $returnedData[] = (int) ($dailyReturned[$key] ?? 0);
                $lateData[] = (int) ($dailyLate[$key] ?? 0);
                $period->addDay();
            }
        } else {
            for ($month = 1; $month <= 12; $month++) {
                $key = Carbon::create($selectedYear, $month, 1)->format('Y-m');
                $labels[] = Carbon::create($selectedYear, $month, 1)->translatedFormat('F');
                $borrowingsData[] = (int) ($dailyBorrowings[$key] ?? 0);
                $borrowedUnitsData[] = (int) ($dailyBorrowedUnits[$key] ?? 0);
                $returnedData[] = (int) ($dailyReturned[$key] ?? 0);
                $lateData[] = (int) ($dailyLate[$key] ?? 0);
            }
        }

        $recentBorrowings = Borrowing::with('details.product')
            ->latest('tanggal_pinjam')
            ->take(5)
            ->get();

        $topProducts = BorrowingDetail::select('product_id', DB::raw('SUM(jumlah) as total_quantity'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        $categoryLabels = $categorySummary->pluck('name')->toArray();
        $categoryData = $categorySummary->pluck('total')->toArray();

        // Recompute totals constrained to the selected period
        $totalBorrowings = (clone $query)->count();
                $totalReturned = (clone $query)->where('status', 'dikembalikan')->count();
                $totalLate = (clone $query)->where(function ($q) {
                    $q->where('status', 'terlambat')
                        ->orWhere(function ($qq) {
                            $qq->where('status', 'dipinjam')
                             ->whereDate('tanggal_kembali', '<', now()->toDateString());
                        })
                        ->orWhere(function ($qq) {
                            $qq->where('status', 'dikembalikan')
                             ->whereColumn('tanggal_kembali_aktual', '>', 'tanggal_kembali');
                        });
                })->count();
                $totalDipinjam = (clone $query)->where('status', 'dipinjam')->count();

        return view('dashboard.index', [
            'totalBarang' => $totalBarang,
            'barangDipinjam' => $barangDipinjam,
            'barangTersedia' => $barangTersedia,
            'totalProduk' => $totalProduk,
            'totalKategori' => $totalKategori,
            'totalUsers' => $totalUsers,
            'totalBorrowings' => $totalBorrowings,
            'totalReturned' => $totalReturned,
            'totalLate' => $totalLate,
            'chartLabels' => $labels,
            'borrowingsData' => $borrowingsData,
            'borrowedUnitsData' => $borrowedUnitsData,
            'returnedData' => $returnedData,
            'lateData' => $lateData,
            'categoryLabels' => $categoryLabels,
            'categoryData' => $categoryData,
            'recentBorrowings' => $recentBorrowings,
            'topProducts' => $topProducts,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'totalDipinjam' => $totalDipinjam,
        ]);
    }
}

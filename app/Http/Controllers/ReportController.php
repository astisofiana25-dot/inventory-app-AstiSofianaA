<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BorrowingsExport;
use App\Exports\CategoriesExport;
use App\Exports\ProductsExport;
use App\Exports\UsersExport;

class ReportController extends Controller
{
    private function applyBorrowingFilters($query, Request $request)
    {
        return $query
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->query('q'), function ($q, $term) {
                $q->where(function ($qq) use ($term) {
                    $qq->where('nama_peminjam', 'like', "%{$term}%")
                        ->orWhereHas('processedBy', function ($q2) use ($term) {
                            $q2->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%");
                        });
                });
            })
            ->when($request->query('product_id'), function ($q, $productId) {
                $q->whereHas('details', function ($qd) use ($productId) {
                    $qd->where('product_id', $productId);
                });
            })
            ->when($request->filled('from') || $request->filled('to'), function ($q) use ($request) {
                $from = $request->input('from');
                $to = $request->input('to');

                try {
                    $start = $from ? Carbon::parse($from)->startOfDay() : null;
                    $end = $to ? Carbon::parse($to)->endOfDay() : null;

                    if ($start && $end) {
                        $q->whereBetween('tanggal_pinjam', [$start, $end])
                          ->whereBetween('tanggal_kembali', [$start, $end]);
                    } elseif ($start) {
                        $q->where('tanggal_pinjam', '>=', $start)
                          ->where('tanggal_kembali', '>=', $start);
                    } elseif ($end) {
                        $q->where('tanggal_pinjam', '<=', $end)
                          ->where('tanggal_kembali', '<=', $end);
                    }
                } catch (\Exception $e) {
                    if ($from && $to) {
                        $q->whereDate('tanggal_pinjam', '>=', $from)
                          ->whereDate('tanggal_pinjam', '<=', $to)
                          ->whereDate('tanggal_kembali', '>=', $from)
                          ->whereDate('tanggal_kembali', '<=', $to);
                    } elseif ($from) {
                        $q->whereDate('tanggal_pinjam', '>=', $from)
                          ->whereDate('tanggal_kembali', '>=', $from);
                    } elseif ($to) {
                        $q->whereDate('tanggal_pinjam', '<=', $to)
                          ->whereDate('tanggal_kembali', '<=', $to);
                    }
                }
            });
    }

    private function applyCategoryFilters($query, Request $request)
    {
        return $query
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->query('q').'%'))
            ->when($request->filled('category_id'), fn ($q, $categoryId) => $q->where('id', $categoryId));
    }

    private function applyProductFilters($query, Request $request)
    {
        return $query
            ->when($request->query('q'), fn ($q, $term) => $q->search($term))
            ->when($request->query('category_id'), fn ($q, $catId) => $q->where('category_id', $catId))
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->query('kondisi'), fn ($q, $kondisi) => $q->where('kondisi_barang', $kondisi));
    }

    public function exportBorrowingsExcel(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $status = $request->query('status');
        $search = $request->query('q');
        $productId = $request->query('product_id');

        return Excel::download(
            new BorrowingsExport($from, $to, $status, $search, $productId),
            'report_borrowings_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function exportBorrowingsPdf(Request $request)
    {
        $query = Borrowing::with('details.product')->orderBy('tanggal_pinjam');
        $query = $this->applyBorrowingFilters($query, $request);

        $borrowings = $query->get();

        // If Dompdf is installed (barryvdh/laravel-dompdf), use it.
        if (class_exists('\\Dompdf\\Dompdf') || class_exists('Barryvdh\\DomPDF\\Facade')) {
            $html = view('reports.borrowings_pdf', compact('borrowings'))->render();

            // prefer the Laravel wrapper if available
            if (class_exists('Barryvdh\\DomPDF\\Facade')) {
                $pdf = \Barryvdh\DomPDF\Facade::loadHTML($html);
                return $pdf->download('report_borrowings_' . now()->format('Ymd_His') . '.pdf');
            }

            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="report_borrowings_' . now()->format('Ymd_His') . '.pdf"',
            ]);
        }

        return back()->with('error', 'PDF export requires the barryvdh/laravel-dompdf package. Run: composer require barryvdh/laravel-dompdf');
    }

    private function applyUserFilters($query, Request $request)
    {
        return $query
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->query('q') . '%';
                $query->where(function ($query) use ($term) {
                    $query->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('employee_id', 'like', $term);
                });
            })
            ->when($request->filled('role'), function ($query) use ($request) {
                $role = $request->query('role');

                // Accept either role id (numeric) or role name (string)
                if (is_numeric($role)) {
                    $query->whereHas('role', fn ($q) => $q->where('id', $role));
                } else {
                    $query->whereHas('role', fn ($q) => $q->where('name', $role));
                }
            });
    }

    public function exportUsersExcel(Request $request)
    {
        $query = User::with('role')->orderBy('name');
        $query = $this->applyUserFilters($query, $request);
        $users = $query->get();

        return Excel::download(
            new UsersExport($users),
            'report_users_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function exportUsersPdf(Request $request)
    {
        $query = User::with('role')->orderBy('name');
        $query = $this->applyUserFilters($query, $request);
        $users = $query->get();
        $html = view('reports.users_pdf', compact('users'))->render();

        if (class_exists('Barryvdh\DomPDF\Facade')) {
            $pdf = \Barryvdh\DomPDF\Facade::loadHTML($html);
            return $pdf->download('report_users_' . now()->format('Ymd_His') . '.pdf');
        }

        if (class_exists('\Dompdf\Dompdf')) {
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="report_users_' . now()->format('Ymd_His') . '.pdf"',
            ]);
        }

        return back()->with('error', 'PDF export requires the barryvdh/laravel-dompdf package. Run: composer require barryvdh/laravel-dompdf');
    }

    public function exportCategoriesExcel(Request $request)
    {
        $query = Category::withCount('products')->orderBy('name');
        $query = $this->applyCategoryFilters($query, $request);
        $categories = $query->get();

        return Excel::download(
            new CategoriesExport($categories),
            'report_categories_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function exportCategoriesPdf(Request $request)
    {
        $query = Category::withCount('products')->orderBy('name');
        $query = $this->applyCategoryFilters($query, $request);
        $categories = $query->get();
        $html = view('reports.categories_pdf', compact('categories'))->render();

        if (class_exists('Barryvdh\DomPDF\Facade')) {
            $pdf = \Barryvdh\DomPDF\Facade::loadHTML($html);
            return $pdf->download('report_categories_' . now()->format('Ymd_His') . '.pdf');
        }

        if (class_exists('\Dompdf\Dompdf')) {
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="report_categories_' . now()->format('Ymd_His') . '.pdf"',
            ]);
        }

        return back()->with('error', 'PDF export requires the barryvdh/laravel-dompdf package. Run: composer require barryvdh/laravel-dompdf');
    }

    public function exportProductsExcel(Request $request)
    {
        $query = Product::with('category')->orderBy('nama_barang');
        $query = $this->applyProductFilters($query, $request);
        $products = $query->get();

        return Excel::download(
            new ProductsExport($products),
            'report_products_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function exportProductsPdf(Request $request)
    {
        $query = Product::with('category')->orderBy('nama_barang');
        $query = $this->applyProductFilters($query, $request);
        $products = $query->get();
        $html = view('reports.products_pdf', compact('products'))->render();

        if (class_exists('Barryvdh\DomPDF\Facade')) {
            $pdf = \Barryvdh\DomPDF\Facade::loadHTML($html);
            return $pdf->download('report_products_' . now()->format('Ymd_His') . '.pdf');
        }

        if (class_exists('\Dompdf\Dompdf')) {
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="report_products_' . now()->format('Ymd_His') . '.pdf"',
            ]);
        }

        return back()->with('error', 'PDF export requires the barryvdh/laravel-dompdf package. Run: composer require barryvdh/laravel-dompdf');
    }

    public function previewBorrowingsPdf(Request $request)
    {
        $query = Borrowing::with('details.product')->orderBy('tanggal_pinjam');
        $query = $this->applyBorrowingFilters($query, $request);

        $borrowings = $query->get();

        $html = view('reports.borrowings_pdf', compact('borrowings'))->render();

        if (class_exists('Barryvdh\\DomPDF\\Facade')) {
            $pdf = \Barryvdh\DomPDF\Facade::loadHTML($html);
            return $pdf->stream('preview.pdf');
        }

        if (class_exists('\\Dompdf\\Dompdf')) {
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            return response($dompdf->output(), 200, ['Content-Type' => 'application/pdf']);
        }

        return back()->with('error', 'PDF preview requires the barryvdh/laravel-dompdf package. Run: composer require barryvdh/laravel-dompdf');
    }

    public function exportBorrowingsXlsx(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $status = $request->query('status');
        $search = $request->query('q');
        $productId = $request->query('product_id');

        return Excel::download(
            new BorrowingsExport($from, $to, $status, $search, $productId),
            'report_borrowings_' . now()->format('Ymd_His') . '.xlsx'
        );
    }
}

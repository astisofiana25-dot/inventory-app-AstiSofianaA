<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Notification;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrowing::with('details.product', 'processedBy');

        // Staff hanya lihat peminjaman mereka sendiri
        if ($request->user()->hasRole('staff')) {
            $query->where('processed_by', $request->user()->id);
        }

        $borrowings = $query
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->query('q'), function ($q, $term) {
                $q->where('nama_peminjam', 'like', "%{$term}%");
            })
            ->when($request->query('from') || $request->query('to'), function ($q) use ($request) {
                $from = $request->query('from');
                $to = $request->query('to');
                if ($from && $to) {
                    try {
                        $start = Carbon::parse($from)->startOfDay();
                        $end = Carbon::parse($to)->endOfDay();
                        $q->whereBetween('tanggal_pinjam', [$start, $end])
                          ->whereBetween('tanggal_kembali', [$start, $end]);
                    } catch (\Exception $e) {
                        $q->whereDate('tanggal_pinjam', '>=', $from)
                          ->whereDate('tanggal_pinjam', '<=', $to)
                          ->whereDate('tanggal_kembali', '>=', $from)
                          ->whereDate('tanggal_kembali', '<=', $to);
                    }
                } elseif ($from) {
                    try {
                        $start = Carbon::parse($from)->startOfDay();
                        $q->where('tanggal_pinjam', '>=', $start)
                          ->where('tanggal_kembali', '>=', $start);
                    } catch (\Exception $e) {
                        $q->whereDate('tanggal_pinjam', '>=', $from)
                          ->whereDate('tanggal_kembali', '>=', $from);
                    }
                } elseif ($to) {
                    try {
                        $end = Carbon::parse($to)->endOfDay();
                        $q->where('tanggal_pinjam', '<=', $end)
                          ->where('tanggal_kembali', '<=', $end);
                    } catch (\Exception $e) {
                        $q->whereDate('tanggal_pinjam', '<=', $to)
                          ->whereDate('tanggal_kembali', '<=', $to);
                    }
                }
            })
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        $products = Product::where('stok', '>', 0)->orderBy('nama_barang')->get();

        return view('borrowings.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_peminjam' => ['required', 'string', 'max:255'],
            'nomor_telepon' => ['required', 'string', 'max:25'],
            'tanggal_pinjam' => ['required', 'date'],
            'tanggal_kembali' => ['required', 'date', 'after_or_equal:tanggal_pinjam'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.jumlah' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $borrowing = Borrowing::create([
                'nama_peminjam' => $validated['nama_peminjam'],
                'nomor_telepon' => $validated['nomor_telepon'] ?? null,
                'processed_by' => $request->user()->id,
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'tanggal_kembali' => $validated['tanggal_kembali'] ?? null,
                'keterangan' => $validated['keterangan'] ?? null,
                'status' => 'dipinjam',
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if ($product->stok < $item['jumlah']) {
                    abort(422, "Stok {$product->nama_barang} tidak mencukupi.");
                }

                BorrowingDetail::create([
                    'borrowing_id' => $borrowing->id,
                    'product_id' => $product->id,
                    'jumlah' => $item['jumlah'],
                ]);

                $product->decrement('stok', $item['jumlah']);
            }

            Notification::create([
                'user_id' => $request->user()->id,
                'title' => 'Peminjaman dicatat',
                'message' => 'Peminjaman barang sudah berhasil dicatat.',
                'type' => 'borrow_created',
                'data' => ['borrowing_id' => $borrowing->id],
            ]);

            User::whereHas('role', function ($q) {
                $q->where('name', 'admin');
            })->each(function ($admin) use ($borrowing, $request) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Peminjaman staf',
                    'message' => "{$request->user()->name} meminjam barang pada {$borrowing->tanggal_pinjam->translatedFormat('d M Y')}",
                    'type' => 'staff_borrowed',
                    'data' => ['borrowing_id' => $borrowing->id, 'staff_id' => $request->user()->id],
                ]);
            });
        });

        return redirect()->route('borrowings.index')->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function show(Borrowing $borrowing)
    {
        $borrowing->load('details.product', 'processedBy');

        // Compute lateness independent of current user so detail always shows correct status
        $isLate = false;
        $lateDays = 0;
        if ($borrowing->tanggal_kembali) {
            $due = \Carbon\Carbon::parse($borrowing->tanggal_kembali);
            if ($borrowing->status === 'terlambat') {
                $isLate = true;
                if ($borrowing->tanggal_kembali_aktual) {
                    $lateDays = \Carbon\Carbon::parse($borrowing->tanggal_kembali_aktual)->diffInDays($due);
                } else {
                    $lateDays = \Carbon\Carbon::now()->diffInDays($due);
                }
            } elseif ($borrowing->status === 'dipinjam' && $due->isPast()) {
                $isLate = true;
                $lateDays = \Carbon\Carbon::now()->diffInDays($due);
            } elseif ($borrowing->status === 'dikembalikan' && $borrowing->tanggal_kembali_aktual && \Carbon\Carbon::parse($borrowing->tanggal_kembali_aktual)->gt($due)) {
                $isLate = true;
                $lateDays = \Carbon\Carbon::parse($borrowing->tanggal_kembali_aktual)->diffInDays($due);
            }
        }

        return view('borrowings.show', compact('borrowing', 'isLate', 'lateDays'));
    }

    public function destroy(Borrowing $borrowing)
    {
        $borrowing->delete();

        return back()->with('success', 'Data peminjaman berhasil dihapus.');
    }

    public function showReturnForm(Borrowing $borrowing)
    {
        $borrowing->load('details.product');

        if ($borrowing->status === 'dikembalikan') {
            return back()->with('error', 'Peminjaman ini sudah dikembalikan.');
        }

        return view('borrowings.return', compact('borrowing'));
    }

    public function staffHistory(Request $request)
    {
        $products = Product::orderBy('nama_barang')->get();

        $borrowings = Borrowing::with('processedBy', 'details.product')
            ->whereHas('processedBy', function ($q) {
                $q->whereHas('role', function ($qr) {
                    $qr->where('name', 'staff');
                });
            })
            ->when($request->query('q'), function ($q, $term) {
                $q->whereHas('processedBy', function ($qq) use ($term) {
                    $qq->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%");
                });
            })
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->query('product_id'), function ($q, $productId) {
                $q->whereHas('details', function ($qd) use ($productId) {
                    $qd->where('product_id', $productId);
                });
            })
            ->when($request->query('pinjam_from') || $request->query('pinjam_to'), function ($q) use ($request) {
                $from = $request->query('pinjam_from');
                $to = $request->query('pinjam_to');
                if ($from && $to) {
                    try {
                        $start = Carbon::parse($from)->startOfDay();
                        $end = Carbon::parse($to)->endOfDay();
                        $q->whereBetween('tanggal_pinjam', [$start, $end])
                          ->whereBetween('tanggal_kembali', [$start, $end]);
                    } catch (\Exception $e) {
                        $q->whereDate('tanggal_pinjam', '>=', $from)->whereDate('tanggal_pinjam', '<=', $to)
                          ->whereDate('tanggal_kembali', '>=', $from)->whereDate('tanggal_kembali', '<=', $to);
                    }
                } elseif ($from) {
                    try {
                        $start = Carbon::parse($from)->startOfDay();
                        $q->where('tanggal_pinjam', '>=', $start)
                          ->where('tanggal_kembali', '>=', $start);
                    } catch (\Exception $e) {
                        $q->whereDate('tanggal_pinjam', '>=', $from)
                          ->whereDate('tanggal_kembali', '>=', $from);
                    }
                } elseif ($to) {
                    try {
                        $end = Carbon::parse($to)->endOfDay();
                        $q->where('tanggal_pinjam', '<=', $end)
                          ->where('tanggal_kembali', '<=', $end);
                    } catch (\Exception $e) {
                        $q->whereDate('tanggal_pinjam', '<=', $to)
                          ->whereDate('tanggal_kembali', '<=', $to);
                    }
                }
            })
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('staff.riwayat', compact('borrowings', 'products'));
    }

    public function returnItem(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->status === 'dikembalikan') {
            return back()->with('error', 'Peminjaman ini sudah dikembalikan.');
        }

        $rules = [
            'tanggal_kembali_aktual' => ['required', 'date', 'after_or_equal:'.$borrowing->tanggal_pinjam],
            'items' => ['required', 'array'],
            'items.*.kondisi' => ['required', 'in:baik,rusak ringan,rusak berat'],
            'items.*.photos' => ['required', 'array', 'min:1', 'max:3'],
            'items.*.photos.*' => ['image', 'mimes:jpeg,png,jpg', 'max:5120'],
            'items.*.keterangan' => ['nullable', 'string', 'max:1000'],
        ];

        $validated = $request->validate($rules);

        // server-side: require keterangan when condition is damaged
        foreach ($validated['items'] as $idx => $it) {
            if (in_array($it['kondisi'], ['rusak ringan', 'rusak berat']) && empty(trim($it['keterangan'] ?? ''))) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    "items.$idx.keterangan" => ['Keterangan wajib jika kondisi rusak.'],
                ]);
            }
        }

        DB::transaction(function () use ($borrowing, $validated, $request) {
            foreach ($borrowing->details as $index => $detail) {
                $input = $validated['items'][$index] ?? null;
                if (!$input) continue;

                $photos = [];
                if (!empty($input['photos'])) {
                    foreach ($input['photos'] as $photo) {

                        $upload = cloudinary()
                            ->uploadApi()
                            ->upload(
                                $photo->getRealPath(),
                                [
                                    'folder' => 'returns'
                                ]
                            );

                        $photos[] = $upload['secure_url'];
                    }

                $updateData = [
                    'kondisi_saat_kembali' => $input['kondisi'],
                    'keterangan' => $input['keterangan'] ?? null,
                ];

                if (Schema::hasColumn('borrowing_details', 'photos')) {
                    $updateData['photos'] = $photos ?: null;
                }

                $detail->update($updateData);

                $detail->product()->increment('stok', $detail->jumlah);
            }

            $borrowing->update([
                'status' => 'dikembalikan',
                'tanggal_kembali_aktual' => $validated['tanggal_kembali_aktual'],
            ]);
        });

        return redirect()->route('borrowings.show', $borrowing)->with('success', 'Barang berhasil dikembalikan dan kondisi tersimpan.');
    }
}

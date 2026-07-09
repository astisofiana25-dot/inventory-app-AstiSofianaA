<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')
            ->search($request->query('q'))
            ->when($request->query('category_id'), fn ($q, $catId) => $q->where('category_id', $catId))
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->query('kondisi'), fn ($q, $kondisi) => $q->where('kondisi_barang', $kondisi));

        if ($request->user()?->hasRole('staff')) {
            $query->where(function ($q) use ($request) {
                $q->where('status', 'approved')
                  ->orWhere('created_by', $request->user()->id);
            });
        }

        $products = $query
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::withCount('products')->orderBy('name')->get();
        $categories->each(function ($category) {
            $category->next_code_number = $this->getNextProductSequence($category->id, $category->code);
        });

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $requiresImage = $request->user()?->hasRole('staff') || $request->user()?->hasRole('admin');
        $validated = $this->validated($request, null, $requiresImage);
        $validated['kode_barang'] = $this->generateProductCode($validated['category_id']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['status'] = $request->user()?->hasRole('staff') ? 'pending' : 'approved';
        $validated['created_by'] = $request->user()?->id;

        Product::create($validated);

        $message = $request->user()?->hasRole('staff')
            ? 'Barang berhasil diajukan. Menunggu persetujuan admin.'
            : 'Barang berhasil ditambahkan.';

        return redirect()->route('products.index')->with('success', $message);
    }

    public function show(Product $product)
    {
        $product->load('category', 'borrowingDetails.borrowing');

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::withCount('products')->orderBy('name')->get();
        $categories->each(function ($category) {
            $category->next_code_number = $this->getNextProductSequence($category->id, $category->code);
        });

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validated($request, $product->id);

        // Staff are not allowed to change product name or category via edit
        if ($request->user()?->hasRole('staff')) {
            $validated['nama_barang'] = $product->nama_barang;
            $validated['category_id'] = $product->category_id;
        }

        if ((int) $validated['category_id'] !== $product->category_id) {
            $validated['kode_barang'] = $this->generateProductCode($validated['category_id']);
        }

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return back()->with('success', 'Barang berhasil dihapus.');
    }

    public function approve(Product $product)
    {
        $product->update(['status' => 'approved']);

        return back()->with('success', 'Barang disetujui.');
    }

    public function generateProductCode(int $categoryId): string
    {
        $category = Category::findOrFail($categoryId);
        $nextNumber = $this->getNextProductSequence($categoryId, $category->code);

        return sprintf('BRG%02d-%s', $nextNumber, $category->code);
    }

    private function getNextProductSequence(int $categoryId, string $categoryCode): int
    {
        $maxSequence = Product::where('category_id', $categoryId)
            ->where('kode_barang', 'like', 'BRG%-%')
            ->selectRaw("MAX(CAST(SUBSTRING(kode_barang, 4, LOCATE('-', kode_barang) - 4) AS UNSIGNED)) as max_seq")
            ->value('max_seq');

        return ($maxSequence ?? 0) + 1;
    }

    private function validated(Request $request, ?int $ignoreId = null, bool $requireImage = false): array
    {
        return $request->validate([
            'kode_barang' => ['nullable', 'string', 'max:50', 'unique:products,kode_barang,' . $ignoreId],
            'nama_barang' => ['required', 'string', 'max:255', 'unique:products,nama_barang,' . $ignoreId],
            'category_id' => ['required', 'exists:categories,id'],
            'stok' => ['required', 'integer', 'min:0'],
            'lokasi_penyimpanan' => ['nullable', 'string', 'max:255'],
            'kondisi_barang' => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'image' => [$requireImage ? 'required' : 'nullable', 'image', 'max:2048'],
        ], [
            'image.required' => 'Foto barang wajib diunggah.',
            'nama_barang.unique' => 'Nama barang sudah ada.',
        ]);
    }
}

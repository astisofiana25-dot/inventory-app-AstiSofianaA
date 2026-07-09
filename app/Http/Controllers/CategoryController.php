<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categoryOptions = Category::orderBy('name')->get(['id', 'name']);

        $categories = Category::withCount('products')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->query('q') . '%';
                $query->where('name', 'like', $term);
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('id', $request->query('category_id'));
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('categories.index', compact('categories', 'categoryOptions'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        Category::create($validated);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
        ]);

        $category->update($validated);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function show(Category $category, Request $request)
    {
        $products = $category->products()
            ->when($request->filled('q'), fn($q) => $q->search($request->query('q')))
            ->orderBy('nama_barang')
            ->paginate(15)
            ->withQueryString();

        return view('categories.show', compact('category', 'products'));
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}

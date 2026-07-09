<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'category_id',
        'stok',
        'lokasi_penyimpanan',
        'kondisi_barang',
        'image',
        'status',
        'created_by',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowingDetails()
    {
        return $this->hasMany(BorrowingDetail::class);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('kode_barang', 'like', "%{$term}%")
              ->orWhere('nama_barang', 'like', "%{$term}%")
              ->orWhere('lokasi_penyimpanan', 'like', "%{$term}%");
        });
    }
}

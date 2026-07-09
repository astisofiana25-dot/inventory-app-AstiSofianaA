<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrowing_id',
        'product_id',
        'jumlah',
        'kondisi_saat_kembali',
        'photos',
        'keterangan',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

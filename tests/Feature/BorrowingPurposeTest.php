<?php

namespace Tests\Feature;

use App\Models\Borrowing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowingPurposeTest extends TestCase
{
    use RefreshDatabase;

    public function test_borrowing_can_store_purpose_note(): void
    {
        $borrowing = Borrowing::create([
            'nama_peminjam' => 'Budi',
            'tanggal_pinjam' => '2026-07-08',
            'tanggal_kembali' => '2026-07-10',
            'keterangan' => 'Dipakai untuk rapat kantor',
            'status' => 'dipinjam',
        ]);

        $this->assertSame('Dipakai untuk rapat kantor', $borrowing->fresh()->keterangan);
    }
}

<?php

namespace Tests\Feature;

use App\Http\Controllers\DashboardController;
use App\Models\Borrowing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_can_build_chart_data_for_a_selected_month(): void
    {
        Borrowing::create([
            'nama_peminjam' => 'Budi',
            'tanggal_pinjam' => '2026-07-10',
            'status' => 'dipinjam',
        ]);

        Borrowing::create([
            'nama_peminjam' => 'Ani',
            'tanggal_pinjam' => '2026-07-20',
            'status' => 'dikembalikan',
        ]);

        $request = Request::create('/dashboard', 'GET', [
            'filter_mode' => 'month',
            'month' => '2026-07',
        ]);

        $controller = new DashboardController();
        $view = $controller->index($request);

        $data = $view->getData();

        $this->assertSame('month', $data['filterMode']);
        $this->assertSame('2026-07', $data['selectedMonth']);
        $this->assertCount(31, $data['chartLabels']);
        $this->assertCount(31, $data['borrowingsData']);
    }
}

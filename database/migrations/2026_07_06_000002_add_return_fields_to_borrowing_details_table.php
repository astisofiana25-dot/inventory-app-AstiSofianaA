<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrowing_details', function (Blueprint $table) {
            $table->json('photos')->nullable()->after('kondisi_saat_kembali');
            $table->text('keterangan')->nullable()->after('photos');
        });
    }

    public function down(): void
    {
        Schema::table('borrowing_details', function (Blueprint $table) {
            $table->dropColumn(['photos', 'keterangan']);
        });
    }
};

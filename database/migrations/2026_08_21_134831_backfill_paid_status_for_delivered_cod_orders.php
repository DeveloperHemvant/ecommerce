<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * One-time data correction: COD orders were never flipped to
     * payment_status=paid on delivery until now, which made them invisible
     * to every revenue report. Any COD order already marked delivered had
     * its cash collected — bring its payment_status in line with reality.
     */
    public function up(): void
    {
        DB::table('orders')
            ->where('payment_method', 'COD')
            ->where('status', 'delivered')
            ->where('payment_status', 'pending')
            ->update(['payment_status' => 'paid']);
    }

    public function down(): void
    {
        // Not reversible — we don't know which of these rows were already
        // "paid" for a different reason before this correction ran.
    }
};

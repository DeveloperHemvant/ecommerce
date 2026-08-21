<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Snapshots a cart at the moment a Razorpay order is created, so a
        // captured payment can still be turned into an Order even if the
        // customer's browser never completes the verify() round trip
        // (tab closed, network drop, etc.) — the webhook reconciles from here.
        Schema::create('pending_checkouts', function (Blueprint $table) {
            $table->id();
            $table->string('razorpay_order_id')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('payload');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_checkouts');
    }
};

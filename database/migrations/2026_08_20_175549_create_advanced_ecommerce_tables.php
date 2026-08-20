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
        // 1. Wishlists Table
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'product_id']);
        });

        // 2. Coupons Table
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('type')->default('percent'); // 'percent', 'fixed'
            $table->decimal('value', 10, 2);
            $table->decimal('min_order_amount', 10, 2)->default(0.00);
            $table->decimal('max_discount_amount', 10, 2)->nullable();
            $table->string('description')->nullable();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Reviews Table
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name');
            $table->integer('rating')->default(5);
            $table->string('title')->nullable();
            $table->text('comment');
            $table->json('photos')->nullable();
            $table->boolean('is_verified_buyer')->default(true);
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });

        // 4. Update Orders Table (Courier & Tracking)
        Schema::table('orders', function (Blueprint $table) {
            $table->string('courier_name')->nullable()->after('status');
            $table->string('tracking_number')->nullable()->after('courier_name');
            $table->string('tracking_url')->nullable()->after('tracking_number');
            $table->date('estimated_delivery')->nullable()->after('tracking_url');
        });

        // 5. Update Order Items Table (Custom Measurements)
        Schema::table('order_items', function (Blueprint $table) {
            $table->json('custom_measurements')->nullable()->after('color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('custom_measurements');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['courier_name', 'tracking_number', 'tracking_url', 'estimated_delivery']);
        });

        Schema::dropIfExists('reviews');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('wishlists');
    }
};

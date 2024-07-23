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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('address_id')->constrained('addresses')->onDelete('cascade');
            $table->decimal('total_price', 15, 2);
            $table->decimal('shipping_price', 15, 2);
            $table->decimal('grand_total', 15, 2);
            $table->string('status')->default('PENDING');
            $table->string('payment_va_name')->nullable();
            $table->string('payment_va_number')->nullable();
            $table->string('shipping_service')->nullable();
            $table->string('shipping_number')->nullable();
            $table->string('transaction_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

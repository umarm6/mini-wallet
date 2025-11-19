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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 20, 2); // Amount sent to receiver
            $table->decimal('commission_fee', 20, 2); // 1.5% fee
            $table->enum('status', ['completed','pending', 'failed']);
            $table->string('reference')->unique(); // UUID for idempotency
            $table->timestamps();

            // Performance indexes
            $table->index('sender_id');
            $table->index('receiver_id');
            $table->index('created_at');
            $table->index(['sender_id', 'created_at']);
            $table->index(['receiver_id', 'created_at']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

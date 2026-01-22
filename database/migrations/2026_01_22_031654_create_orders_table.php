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
            // FK + index (Laravel adds index automatically for foreignId).
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // Only created_at, default current timestamp.
            $table->timestamp('created_at')->useCurrent();

            // For orders_count performance.
            // Explicitly mention to tell not only as FK but for better count.
            $table->index('user_id', 'orders_user_id_idx');
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

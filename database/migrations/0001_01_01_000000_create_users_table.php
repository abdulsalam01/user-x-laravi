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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['user', 'manager', 'administrator'])->default('user');
            $table->boolean('active')->default(true);
            $table->rememberToken();
            $table->timestamps();

            // Listing/sorting performance (active + sortBy).
            $table->index(['active', 'created_at'], 'users_active_created_at_idx');
            $table->index(['active', 'name'], 'users_active_name_idx');
            $table->index(['active', 'email'], 'users_active_email_idx');
            $table->index(['role', 'active'], 'users_role_active_idx'); // Retrieve by role, to get coherence users for sending mail.

            // Enable FULLTEXT and use MATCH AGAINST (only MySQL, since use sqlite for simplicity - comment it).
            // $table->fullText(['name', 'email'], 'users_name_email_fulltext');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

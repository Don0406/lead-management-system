<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <--- ADD THIS LINE

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This alters the column to include 'guest'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'client', 'sales_manager', 'sales_rep', 'guest') DEFAULT 'guest'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert to original roles if needed
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'client', 'sales_manager', 'sales_rep') DEFAULT 'admin'");
        });
    }
};
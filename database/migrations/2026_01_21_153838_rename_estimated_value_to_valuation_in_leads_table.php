<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('leads', function (Blueprint $table) {
        // This renames the actual column in MySQL
        $table->renameColumn('estimated_value', 'valuation');
    });
}

public function down()
{
    Schema::table('leads', function (Blueprint $table) {
        $table->renameColumn('valuation', 'estimated_value');
    });
}
};

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
        Schema::table('inventory_adjustments', function (Blueprint $table) {
            $table->integer('old_quantity')->nullable()->after('notes');
            $table->integer('new_quantity')->nullable()->after('old_quantity');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_adjustments', function (Blueprint $table) {
            $table->dropColumn(['old_quantity', 'new_quantity']);
        });
    }
};

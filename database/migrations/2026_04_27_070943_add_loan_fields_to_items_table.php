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
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('is_loanable')->default(true)->after('status');
            $table->string('loan_type')->default('daily')->after('is_loanable'); // daily, hourly
            $table->integer('max_loan_duration')->nullable()->after('loan_type'); // hours or days
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['is_loanable', 'loan_type', 'max_loan_duration']);
        });
    }
};

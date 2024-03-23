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
        Schema::create('daily_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamp('date')->index('idx_daily_records_date');
            $table->integer('male_count', false, true);
            $table->integer('female_count', false, true);
            $table->float('male_avg_age', 3);
            $table->float('female_avg_age', 3);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_records');
    }
};

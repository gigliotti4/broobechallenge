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
        Schema::create('metric_history_runs', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('accessibility_metric')->nullable();
            $table->string('pwa_metric')->nullable();
            $table->string('performance_metric')->nullable();
            $table->string('seo_metric')->nullable();
            $table->string('best_practices_metric')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metric_history_runs');
    }
};

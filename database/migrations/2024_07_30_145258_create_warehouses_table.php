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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('sequence_format');
            $table->string('owner');
            $table->string('project');
            $table->string('spk_number');
            $table->text('location');
            $table->string('logistic');
            $table->string('supervisor');
            $table->string('site_manager');
            $table->string('project_manager');
            $table->string('head_logistic');
            $table->string('branch_manager');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};

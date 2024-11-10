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
        Schema::table('warehouses', function (Blueprint $table) {
            $table->string('site_engineer');
            $table->string('asset_controller');
            $table->string('head_purchasing');
            $table->string('project_management');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->string('site_engineer')->drop();
            $table->string('asset_controller')->drop();
            $table->string('head_purchasing')->drop();
            $table->string('project_management')->drop();
        });
    }
};

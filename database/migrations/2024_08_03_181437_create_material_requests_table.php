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
        Schema::create('material_requests', function (Blueprint $table) {
            $table->id();
            $table->string('mr_number');
            $table->date('date');
            $table->foreignId('warehouse_id')->references('id')->on('warehouses')->onDelete('RESTRICT');
            $table->foreignId('requested_by')->references('id')->on('users')->onDelete('RESTRICT');
            $table->foreignId('status_id')->references('id')->on('status')->onDelete('RESTRICT');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_requests');
    }
};

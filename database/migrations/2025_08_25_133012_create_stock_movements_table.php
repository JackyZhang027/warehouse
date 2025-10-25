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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('movement_type', ['in', 'out', 'transfer']);
            $table->foreignId('warehouse_from_id')->references('id')->on('warehouses')->onDelete('CASCADE');
            $table->foreignId('warehouse_to_id')->references('id')->on('warehouses')->onDelete('CASCADE');
            $table->foreignId('created_by')->references('id')->on('users')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};

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
        Schema::create('stock_cards', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('warehouse_id')->references('id')->on('warehouses')->onDelete('CASCADE');
            $table->foreignId('item_id')->references('id')->on('items')->onDelete('CASCADE');
            $table->float('qty')->default(0);
            $table->enum('type', ['in', 'out', 'adjustment']);
            $table->bigInteger('ref_id');
            $table->string('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_cards');
    }
};

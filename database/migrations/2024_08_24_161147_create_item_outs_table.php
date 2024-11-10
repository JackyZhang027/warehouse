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
        Schema::create('item_outs', function (Blueprint $table) {
            $table->id();
            $table->string('io_number')->unique();
            $table->date('date');
            $table->foreignId('warehouse_id')->references('id')->on('warehouses')->onDelete('CASCADE');
            $table->text('remark')->nullable();
            $table->foreignId('created_by')->references('id')->on('users')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_outs');
    }
};

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
        Schema::create('material_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mr_id')->references('id')->on('material_requests')->onDelete('CASCADE');
            $table->foreignId('item_id')->references('id')->on('items')->onDelete('CASCADE');
            $table->float('qty');
            $table->float('do_qty')->default(0);
            $table->float('received_qty')->default(0);
            $table->date('date_needed');
            $table->string('boq_code')->nullable();
            $table->boolean('check_m');
            $table->boolean('check_t');
            $table->boolean('check_he');
            $table->boolean('check_c');
            $table->boolean('check_o');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_request_items');
    }
};

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
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->id();
            $table->string('do_number')->unique();
            $table->foreignId('warehouse_id')->references('id')->on('warehouses')->onDelete('RESTRICT');
            $table->date('date');
            $table->string('police_no');
            $table->string('receipent');
            $table->text('address');
            $table->foreignId('created_by')->references('id')->on('users')->onDelete('RESTRICT');
            $table->timestamps();
        });
        Schema::create('delivery_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_order_id')->constrained('delivery_orders')->onDelete('cascade');
            $table->foreignId('material_request_item_id')->constrained('material_request_items')->onDelete('cascade');
            $table->float('qty');
            $table->float('received_qty')->nullable()->default(0);
            $table->float('balance')->storedAs('qty - received_qty')->nullable();
            $table->string("po_number")->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_order_items');
        Schema::dropIfExists('delivery_orders');
    }
};

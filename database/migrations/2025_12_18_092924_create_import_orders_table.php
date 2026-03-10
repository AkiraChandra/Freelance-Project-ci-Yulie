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
        Schema::create('import_orders', function (Blueprint $table) {
            $table->id();
            $table->string('import_order_number')->unique();
            $table->date('order_date');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('bl_number')->nullable(); // B/L NO
            $table->string('product_name')->nullable(); // NAMA BARANG
            $table->string('shipping_line')->nullable(); // PELAYARAN
            $table->string('vessel_name')->nullable(); // NAMA KAPAL
            $table->string('voy_number')->nullable(); // VOY KAPAL
            $table->date('vessel_arrival_date')->nullable(); // TGL KAPAL TIBA
            $table->string('pib_number')->nullable(); // PIB AJU
            $table->string('party')->nullable();
            $table->string('port')->nullable(); // PELABUHAN/GUDANG
            $table->date('do_date')->nullable(); // TGL DO
            $table->string('container_number')->nullable(); // NO CONTAINER
            $table->date('demurrage_date')->nullable(); // TGL DEMURRAGE/DETENTION
            $table->date('release_date')->nullable(); // TGL PENGELUARAN DARI PELABUHAN
            $table->date('container_return_date')->nullable(); // TGL PENGEMBALIAN CONTAINER DARI PABRIK
            $table->string('trucking_vendor')->nullable();
            $table->text('issue')->nullable(); // MASALAH
            $table->enum('status', ['on going', 'completed', 'cancelled'])->default('on going');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_orders');
    }
};

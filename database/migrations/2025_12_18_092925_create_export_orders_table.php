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
        Schema::create('export_orders', function (Blueprint $table) {
            $table->id();
            $table->string('export_order_number')->unique();
            $table->date('order_date');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('shipping_number')->nullable(); // NO SHIPPING
            $table->string('do_number')->nullable(); // NO DO
            $table->string('product_name')->nullable(); // NAMA BARANG
            $table->string('shipping_line')->nullable(); // PELAYARAN
            $table->string('vessel_name')->nullable(); // NAMA KAPAL
            $table->string('voy_number')->nullable(); // VOY KAPAL
            $table->date('closing_date')->nullable(); // TGL CLOSING
            $table->string('peb_number')->nullable(); // PEB AJU
            $table->string('party')->nullable();
            $table->string('depo')->nullable();
            $table->string('container_number')->nullable(); // NO CONTAINER
            $table->date('pickup_date')->nullable(); // Tgl Pengantaran
            $table->date('return_date')->nullable(); // Tgl Penarikan Keluar
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
        Schema::dropIfExists('export_orders');
    }
};

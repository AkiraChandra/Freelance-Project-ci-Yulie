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
        Schema::create('order_containers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->enum('order_type', ['import', 'export']); // Jenis order
            $table->integer('container_group')->default(1); // Group number (untuk multiple jenis dalam 1 order)
            
            // Container Info
            $table->string('container_size'); // 20', 40', LCL
            $table->string('container_number')->nullable(); // No Container (kosong untuk LCL)
            $table->string('container_type')->nullable(); // GP, OT, HC, RF (kosong untuk LCL)
            $table->string('vendor'); // Vendor name (dari dropdown vendors table)
            
            // Combo & Combine (foreign keys ke order_containers lain)
            $table->foreignId('combo_with')->nullable()->constrained('order_containers')->onDelete('set null');
            $table->foreignId('combine_with_container_id')->nullable()->constrained('order_containers')->onDelete('set null');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['order_id', 'container_group']);
            $table->index('container_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_containers');
    }
};

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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique(); // Order ID: ORD-001, ORD-002
            $table->enum('type', ['import', 'export', 'both']); // Tipe order
            $table->date('order_date');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->enum('status', ['on going', 'completed', 'cancelled'])->default('on going');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

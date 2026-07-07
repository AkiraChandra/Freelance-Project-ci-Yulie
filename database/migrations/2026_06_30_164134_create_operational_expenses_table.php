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
        Schema::create('operational_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('operational_staff_assignments')->onDelete('cascade');
            $table->date('expense_date'); // Tanggal biaya (30 Apr, 1 Mei, 2 Mei)
            $table->decimal('amount', 15, 2); // Nominal (50000, 2500, 70000)
            $table->string('description'); // Keterangan (tes, adm, pengeluaran barang, dll)
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['assignment_id', 'expense_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operational_expenses');
    }
};

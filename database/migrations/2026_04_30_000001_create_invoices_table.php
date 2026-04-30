<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->enum('order_type', ['import', 'export']);
            $table->unsignedBigInteger('order_id');
            $table->json('sections'); // [{name: "INV Reimbursement", items: [{label: "Port Charges", amount: 11846719}]}]
            $table->decimal('panjar', 15, 2)->default(0);
            $table->boolean('include_tax')->default(false);
            $table->decimal('tax_percentage', 5, 2)->default(1.1);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

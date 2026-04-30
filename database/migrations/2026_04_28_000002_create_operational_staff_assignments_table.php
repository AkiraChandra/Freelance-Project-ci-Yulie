<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operational_staff_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operational_staff_id')->constrained('operational_staffs')->onDelete('cascade');
            $table->enum('order_type', ['export', 'import']);
            $table->unsignedBigInteger('order_id'); // references export_orders.id or import_orders.id
            $table->decimal('fee', 15, 2)->default(0); // gaji/biaya per orderan
            $table->text('notes')->nullable();
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operational_staff_assignments');
    }
};

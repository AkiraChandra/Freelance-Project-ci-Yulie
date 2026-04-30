<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('nota_number')->nullable()->after('order_id');
            $table->string('invoice_title')->nullable()->after('nota_number');
            $table->string('vessel_name')->nullable()->after('invoice_title');
            $table->string('vessel_date')->nullable()->after('vessel_name');
            $table->string('destination')->nullable()->after('vessel_date');
            $table->string('party_display')->nullable()->after('destination');
            $table->string('product_name')->nullable()->after('party_display');
            $table->string('tonage')->nullable()->after('product_name');
            $table->string('merk')->nullable()->after('tonage');
            $table->string('container_display')->nullable()->after('merk');
            $table->unsignedTinyInteger('revision')->default(0)->after('container_display');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'nota_number', 'invoice_title', 'vessel_name', 'vessel_date',
                'destination', 'party_display', 'product_name', 'tonage',
                'merk', 'container_display', 'revision',
            ]);
        });
    }
};

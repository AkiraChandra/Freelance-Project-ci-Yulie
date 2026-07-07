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
        Schema::table('import_orders', function (Blueprint $table) {
            $table->dropColumn(['container_number', 'trucking_vendor', 'party']);
        });

        Schema::table('export_orders', function (Blueprint $table) {
            $table->dropColumn(['container_number', 'trucking_vendor', 'party']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('import_orders', function (Blueprint $table) {
            $table->string('container_number')->nullable();
            $table->string('trucking_vendor')->nullable();
            $table->string('party')->nullable();
        });

        Schema::table('export_orders', function (Blueprint $table) {
            $table->string('container_number')->nullable();
            $table->string('trucking_vendor')->nullable();
            $table->string('party')->nullable();
        });
    }
};

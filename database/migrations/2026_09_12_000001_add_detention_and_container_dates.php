<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_containers', function (Blueprint $table) {
            $table->date('release_date')->nullable()->after('container_type');
            $table->date('return_date')->nullable()->after('release_date');
        });

        Schema::table('import_orders', function (Blueprint $table) {
            $table->date('detention_deadline')->nullable()->after('do_date');
        });

        Schema::table('export_orders', function (Blueprint $table) {
            $table->date('detention_deadline')->nullable()->after('closing_date');
        });
    }

    public function down(): void
    {
        Schema::table('order_containers', function (Blueprint $table) {
            $table->dropColumn(['release_date', 'return_date']);
        });

        Schema::table('import_orders', function (Blueprint $table) {
            $table->dropColumn('detention_deadline');
        });

        Schema::table('export_orders', function (Blueprint $table) {
            $table->dropColumn('detention_deadline');
        });
    }
};

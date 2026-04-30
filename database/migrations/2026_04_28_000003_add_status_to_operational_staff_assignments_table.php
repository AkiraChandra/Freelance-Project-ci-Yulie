<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('operational_staff_assignments', function (Blueprint $table) {
            // 1 = request (pending), 2 = accepted (approved/paid), 3 = declined
            $table->tinyInteger('status')->default(1)->after('notes');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null')->after('status');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
        });
    }

    public function down(): void
    {
        Schema::table('operational_staff_assignments', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['status', 'reviewed_by', 'reviewed_at']);
        });
    }
};

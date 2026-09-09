<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('invoice_type', ['reimbursement', 'invoice'])->default('invoice')->after('invoice_title');
            $table->json('taxable_sections')->nullable()->after('tax_percentage')
                ->comment('Array of section indices to include in tax calculation. e.g. [1, 2] for section 2 and 3');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['invoice_type', 'taxable_sections']);
        });
    }
};

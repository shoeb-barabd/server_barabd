<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('locations', function (Blueprint $table) {
            if (! Schema::hasColumn('locations', 'currency_code')) {
                $table->string('currency_code', 3)->default('BDT')->after('country_id');
            }
            if (! Schema::hasColumn('locations', 'tax_rate_percent')) {
                $table->decimal('tax_rate_percent', 5, 2)->default(0)->after('currency_code'); // e.g., 15.00
            }
        });
    }

    public function down(): void {
        Schema::table('locations', function (Blueprint $table) {
            if (Schema::hasColumn('locations', 'tax_rate_percent')) {
                $table->dropColumn('tax_rate_percent');
            }
            if (Schema::hasColumn('locations', 'currency_code')) {
                $table->dropColumn('currency_code');
            }
        });
    }
};

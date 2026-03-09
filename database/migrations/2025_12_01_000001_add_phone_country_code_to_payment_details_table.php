<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payment_details', function (Blueprint $table) {
            $table->string('phone_country_code', 10)->nullable()->after('phone');
            $table->string('phone_full', 50)->nullable()->after('phone_country_code');
        });
    }

    public function down(): void
    {
        Schema::table('payment_details', function (Blueprint $table) {
            $table->dropColumn(['phone_country_code', 'phone_full']);
        });
    }
};

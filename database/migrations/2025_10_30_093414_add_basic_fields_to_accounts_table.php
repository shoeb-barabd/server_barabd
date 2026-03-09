<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('email', 150)->nullable()->after('country_id');
            $table->string('phone', 50)->nullable()->after('email');
            $table->string('website', 150)->nullable()->after('phone');
            $table->string('address', 500)->nullable()->after('website');
            $table->boolean('is_active')->default(true)->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn(['email','phone','website','address','is_active']);
        });
    }
};

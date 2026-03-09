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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->foreignId('account_id')->nullable()->after('id')->constrained('accounts')->nullOnDelete();
            $table->string('role')->default('customer')->after('email'); // temporary; later spatie
            $table->enum('status', ['active','invited','disabled'])->default('active')->after('role');
            $table->string('phone')->nullable()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};

<?php

// database/migrations/xxxx_add_google_columns_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('users', function (Blueprint $table) {
      if (!Schema::hasColumn('users','google_id')) $table->string('google_id')->nullable()->index();
      if (!Schema::hasColumn('users','avatar'))    $table->string('avatar')->nullable();
      if (!Schema::hasColumn('users','email_verified_at')) $table->timestamp('email_verified_at')->nullable();
    });
  }
  public function down(): void {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn(['google_id','avatar','email_verified_at']);
    });
  }
};

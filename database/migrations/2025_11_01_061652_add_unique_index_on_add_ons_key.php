<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected function indexExists(string $table, string $index): bool
    {
        $rows = DB::select("SHOW INDEX FROM `$table` WHERE Key_name = ?", [$index]);
        return !empty($rows);
    }

    public function up(): void
    {
        // Only add if the named unique index doesn't already exist
        if (! $this->indexExists('add_ons', 'add_ons_key_unique')) {
            Schema::table('add_ons', function (Blueprint $table) {
                $table->unique('key', 'add_ons_key_unique');
            });
        }
    }

    public function down(): void
    {
        // Only drop if it exists
        if ($this->indexExists('add_ons', 'add_ons_key_unique')) {
            Schema::table('add_ons', function (Blueprint $table) {
                $table->dropUnique('add_ons_key_unique');
            });
        }
    }
};

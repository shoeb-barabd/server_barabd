<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('products', function (Blueprint $table) {
            // Make sure it's nullable and add FK to categories (no cascade delete on product)
            // If there is already data, this simply adds the FK.
            if (! Schema::hasColumn('products', 'category_id')) {
                $table->foreignId('category_id')->nullable();
            }
            // Add the foreign constraint if not present
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::table('products', function (Blueprint $table) {
            // Drop only the constraint; keep the column as you had it before
            $table->dropForeign(['category_id']);
        });
    }
};

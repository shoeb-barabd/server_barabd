<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Optional but recommended: make sure both tables use InnoDB
        // DB::statement('ALTER TABLE categories ENGINE=InnoDB');
        // DB::statement('ALTER TABLE products ENGINE=InnoDB');

        // 1) Clean any orphan/invalid category_id values (set them to NULL)
        DB::statement("
            UPDATE products p
            LEFT JOIN categories c ON c.id = p.category_id
            SET p.category_id = NULL
            WHERE p.category_id IS NOT NULL AND c.id IS NULL
        ");

        Schema::table('products', function (Blueprint $table) {
            // 2) Ensure correct type & nullability (must match categories.id = unsigned BIGINT)
            $table->unsignedBigInteger('category_id')->nullable()->change();

            // (Create an index first; MySQL requires it for FK)
            $table->index('category_id', 'products_category_id_idx');
        });

        Schema::table('products', function (Blueprint $table) {
            // 3) Add the foreign key (explicit name for reliable down())
            $table->foreign('category_id', 'products_category_id_fk')
                ->references('id')
                ->on('categories')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop FK then the helper index
            $table->dropForeign('products_category_id_fk');
            $table->dropIndex('products_category_id_idx');
            // (we keep column as-is; no destructive changes on down)
        });
    }
};

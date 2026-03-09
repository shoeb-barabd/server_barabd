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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->after('category_id')->constrained()->nullOnDelete();
            $table->foreignId('location_id')->nullable()->after('product_id')->constrained()->nullOnDelete();
            $table->foreignId('billing_cycle_id')->nullable()->after('location_id')->constrained()->nullOnDelete();

            $table->string('product_name')->nullable()->after('currency');
            $table->json('config')->nullable()->after('product_name');     // selected features/add-ons
            $table->json('line_items')->nullable()->after('config');       // quote line breakdown
            $table->json('meta')->nullable()->after('line_items');         // misc context (location, cycle, notes)
            $table->timestamp('paid_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['paid_at', 'meta', 'line_items', 'config', 'product_name']);

            $table->dropConstrainedForeignId('billing_cycle_id');
            $table->dropConstrainedForeignId('location_id');
            $table->dropConstrainedForeignId('product_id');
            $table->dropConstrainedForeignId('category_id');
            $table->dropConstrainedForeignId('user_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // --- Products: link to WHMCS product ---
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('whmcs_product_id')->nullable()->after('is_active');
        });

        // --- Billing Cycles: WHMCS cycle key ---
        Schema::table('billing_cycles', function (Blueprint $table) {
            $table->string('whmcs_cycle', 30)->nullable()->after('is_active');
        });

        // --- Payments: store WHMCS order / client / service IDs ---
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedInteger('whmcs_client_id')->nullable()->after('paid_at');
            $table->unsignedInteger('whmcs_order_id')->nullable()->after('whmcs_client_id');
            $table->unsignedInteger('whmcs_invoice_id')->nullable()->after('whmcs_order_id');
            $table->unsignedInteger('whmcs_service_id')->nullable()->after('whmcs_invoice_id');
            $table->string('whmcs_status', 30)->nullable()->after('whmcs_service_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('whmcs_product_id');
        });

        Schema::table('billing_cycles', function (Blueprint $table) {
            $table->dropColumn('whmcs_cycle');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'whmcs_client_id',
                'whmcs_order_id',
                'whmcs_invoice_id',
                'whmcs_service_id',
                'whmcs_status',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('add_on_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('add_on_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained();
            $table->foreignId('billing_cycle_id')->constrained();
            $table->decimal('unit_price', 10, 2);
            $table->timestamps();
            $table->unique(['add_on_id', 'location_id', 'billing_cycle_id'], 'addon_price_unique');
        });
    }

    public function down(): void {
        Schema::dropIfExists('add_on_prices');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customize_feature_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customize_feature_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->foreignId('billing_cycle_id')->constrained()->cascadeOnDelete();
            $table->decimal('included_value', 12, 3)->default(0);
            $table->decimal('step', 12, 3)->default(1);
            $table->decimal('price_per_step', 12, 4)->default(0);
            $table->timestamps();

            $table->unique(
                ['customize_feature_id', 'location_id', 'billing_cycle_id'],
                'customize_feature_price_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customize_feature_prices');
    }
};

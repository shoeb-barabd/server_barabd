<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('feature_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_feature_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->foreignId('billing_cycle_id')->constrained()->cascadeOnDelete();

            // Linear pricing model:
            // billable = max(selected - included_value, 0)
            // steps = ceil(billable / step)
            // line_total = steps * price_per_step
            $table->decimal('included_value', 12, 3)->default(0);
            $table->decimal('step', 12, 3)->default(1);
            $table->decimal('price_per_step', 12, 4)->default(0);

            $table->timestamps();

            $table->unique(
                ['product_feature_id','location_id','billing_cycle_id'],
                'feature_price_unique'
            );
        });
    }

    public function down(): void {
        Schema::dropIfExists('feature_prices');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('key');               // storage_gb, bandwidth_gb, websites, ssl
            $table->string('label');
            $table->enum('input_type', ['number','boolean','select'])->default('number');
            $table->string('unit')->nullable();  // GB, sites, etc.
            $table->decimal('min', 12, 3)->nullable();
            $table->decimal('max', 12, 3)->nullable();
            $table->decimal('step', 12, 3)->nullable()->default(1);
            $table->json('options_json')->nullable(); // for 'select'
            $table->boolean('is_required')->default(false);
            $table->timestamps();

            $table->unique(['product_id','key'], 'product_feature_key_unique');
        });
    }

    public function down(): void {
        Schema::dropIfExists('product_features');
    }
};

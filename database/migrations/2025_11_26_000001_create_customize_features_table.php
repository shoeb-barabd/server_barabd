<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customize_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->string('label');
            $table->enum('input_type', ['number', 'boolean', 'select'])->default('number');
            $table->string('unit')->nullable();
            $table->decimal('min', 12, 3)->nullable();
            $table->decimal('max', 12, 3)->nullable();
            $table->decimal('step', 12, 3)->nullable()->default(1);
            $table->json('options_json')->nullable();
            $table->boolean('is_required')->default(false);
            $table->timestamps();

            $table->unique(['category_id', 'key'], 'customize_feature_key_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customize_features');
    }
};

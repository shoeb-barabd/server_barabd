<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('add_ons', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('label');
            $table->text('description')->nullable();
            $table->enum('unit_type', ['one_time', 'recurring'])->default('recurring');
            $table->boolean('is_qty_based')->default(false);
            $table->integer('max_qty')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('add_ons');
    }
};

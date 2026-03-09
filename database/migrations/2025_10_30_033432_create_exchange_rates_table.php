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
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('base_currency_code', 3);
            $table->string('quote_currency_code', 3);
            $table->decimal('rate', 18, 8); // 1 base = rate quote
            $table->date('valid_from');
            $table->date('valid_to')->nullable();
            $table->timestamps();

            $table->foreign('base_currency_code')->references('code')->on('currencies');
            $table->foreign('quote_currency_code')->references('code')->on('currencies');
            $table->unique(['base_currency_code','quote_currency_code','valid_from'], 'xr_unique_window');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};

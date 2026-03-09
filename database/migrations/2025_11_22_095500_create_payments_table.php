<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->string('tran_id')->unique();
        $table->decimal('amount', 10, 2);
        $table->string('currency', 3)->default('BDT');
        $table->string('status')->default('INIT'); // INIT, PENDING, SUCCESS, FAILED, CANCELLED
        $table->string('val_id')->nullable();      // validation ID from SSLCommerz
        $table->text('gateway_response')->nullable(); // raw json
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('domain_tlds', function (Blueprint $table) {
            $table->id();
            $table->string('tld', 20)->unique();          // .com, .net, .xyz
            $table->decimal('register_price', 10, 2);     // 1-year registration price (BDT)
            $table->decimal('renew_price', 10, 2);        // 1-year renewal price
            $table->decimal('transfer_price', 10, 2)->default(0);
            $table->string('currency', 5)->default('BDT');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domain_tlds');
    }
};

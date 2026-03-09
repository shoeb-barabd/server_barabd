<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('domain_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('domain_name');                              // full domain e.g. example.com
            $table->string('sld');                                      // second-level domain e.g. example
            $table->string('tld', 20);                                  // .com
            $table->enum('action', ['register', 'transfer', 'renew'])->default('register');
            $table->unsignedTinyInteger('years')->default(1);
            $table->decimal('amount', 10, 2);
            $table->string('currency', 5)->default('BDT');
            $table->string('status')->default('pending');               // pending, paid, active, expired, cancelled, failed
            $table->string('tran_id')->nullable()->index();             // SSLCommerz transaction
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();

            // OpenProvider fields
            $table->unsignedBigInteger('op_domain_id')->nullable();     // OpenProvider domain ID
            $table->string('op_status')->nullable();                    // ACT, FAI, PEN etc.
            $table->date('registration_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->json('nameservers')->nullable();
            $table->json('registrant')->nullable();                     // WHOIS contact info

            // link to hosting if bought together
            $table->foreignId('linked_hosting_payment_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domain_orders');
    }
};

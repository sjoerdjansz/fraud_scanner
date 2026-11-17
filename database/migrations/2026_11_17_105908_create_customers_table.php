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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at');
            $table->foreignId('scan_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('external_customer_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('phone')->nullable();
            $table->string('ip')->nullable();
            $table->string('iban')->nullable();
            $table->json('products')->nullable();
            $table->json('fraud_reasons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

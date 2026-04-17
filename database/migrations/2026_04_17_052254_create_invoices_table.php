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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('current_status_id');
            $table->morphs('invoiceable');

            $table->string('reference_number')->unique();
            $table->unsignedBigInteger('total_amount')->default(0);

            $table->timestamp('due_date_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->string('payment_token')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

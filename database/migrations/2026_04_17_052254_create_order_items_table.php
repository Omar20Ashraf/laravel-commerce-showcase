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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id');
            $table->foreignId('service_item_id');
            $table->foreignId('current_status_id');

            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('fee');
            $table->unsignedBigInteger('total_amount');

            $table->unsignedInteger('qty')->default(1);

            $table->timestamp('provider_due_date_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

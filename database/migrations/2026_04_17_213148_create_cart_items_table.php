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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('service_item_id');
            $table->string('guest_ip', 45)->nullable();
            $table->unsignedSmallInteger('qty')->default(1);
            $table->timestamps();

            $table->index(['user_id', 'service_item_id'], 'cart_user_service_idx');
            $table->index(['guest_ip', 'service_item_id'], 'cart_guest_service_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};

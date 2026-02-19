<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('shipping_address_id');
            $table->unsignedBigInteger('billing_address_id');
            $table->unsignedBigInteger('card_id');
            $table->enum('status', ['pending', 'paid', 'shipped', 'delivered', 'cancelled']);
            $table->decimal('total', 10, 2);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->cascadeOnUpdate()
                ->noActionOnDelete();

            $table->foreign('shipping_address_id')
                ->references('id')->on('addresses')
                ->cascadeOnUpdate()
                ->noActionOnDelete();

            $table->foreign('billing_address_id')
                ->references('id')->on('addresses')
                ->cascadeOnUpdate()
                ->noActionOnDelete();

            $table->foreign('card_id')
                ->references('id')->on('cards')
                ->cascadeOnUpdate()
                ->noActionOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

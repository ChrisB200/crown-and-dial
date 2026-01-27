<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('watch_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->enum('type', ['in', 'out']);
            $table->integer('quantity');
            $table->string('note')->nullable();
            $table->timestamps();

            $table->foreign('watch_id')->references('id')->on('watches')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};

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
            $table->text('invoice');
            $table->unsignedBigInteger('customer_id');
            $table->string('courier');
            $table->string('service');
            $table->bigInteger('cost_counrier');
            $table->integer('weight');
            $table->string('name');
            $table->bigInteger('phone');
            $table->integer('province');
            $table->integer('city');
            $table->text('address');
            $table->enum('status', array('success', 'pending', 'failed', 'expired'));
            $table->string('snap_token')->nullable();
            $table->bigInteger('grand_total');
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

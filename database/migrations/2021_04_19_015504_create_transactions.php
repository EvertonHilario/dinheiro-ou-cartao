<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('hash')->unique()->nullable();
            $table->decimal('value', 15, 2)->nullable();
            $table->foreignId('payer_id')->references('id')->on('users')->nullable();
            $table->foreignId('payee_id')->references('id')->on('users')->nullable();
            $table->foreignId('transactions_type_id')->references('id')->on('transactions_type')->nullable();
            $table->foreignId('transactions_status_id')->references('id')->on('transactions_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions_notification', function (Blueprint $table) {
            $table->id();
            $table->text('payload_request');
            $table->text('payload_response');
            $table->integer('status')->nullable();
            $table->foreignId('users_id')->references('id')->on('users')->nullable();
            $table->foreignId('transactions_id')->references('id')->on('transactions')->nullable();
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
        Schema::dropIfExists('transactions_notification');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->decimal('value', 15, 2)->nullable();
            $table->string('title');
            $table->foreignId('wallets_id')->references('id')->on('wallets')->nullable();
            $table->foreignId('transactions_id')->references('id')->on('transactions')->nullable();
            $table->foreignId('operations_type_id')->references('id')->on('operations_type')->nullable();
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
        Schema::dropIfExists('operations');
    }
}

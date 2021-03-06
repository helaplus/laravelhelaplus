<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helaplus_transaction', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('amount');
            $table->string('recipient');
            $table->string('reference')->nullable(); 
            $table->integer('status')->default(0); //0 for initiated, 1 accepted , 2 for successful, 3 for failed
            $table->text('details')->nullable();
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
        Schema::dropIfExists('helaplus_transaction');
    }
};

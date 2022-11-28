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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->float('amount');
            $table->float('amount_per_person');
            $table->integer('number_of_people');
            $table->string('link');
            $table->dateTime('expiration_date')->nullable();
            $table->boolean('is_expired');
            $table->boolean('is_valid');
            $table->integer('user_id');
            $table->tinyInteger('type');
            $table->integer('counter')->default(0);
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
        Schema::dropIfExists('payments');
    }
};

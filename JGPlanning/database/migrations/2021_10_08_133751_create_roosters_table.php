<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoostersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooster', function (Blueprint $table) {
            $table->id();
            $table->time('start');
            $table->time('end');
            $table->longText('comment');
            $table->boolean('from_home');
            $table->date('date');
            $table->integer('weekdays');
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
        Schema::dropIfExists('roosters');
    }
}

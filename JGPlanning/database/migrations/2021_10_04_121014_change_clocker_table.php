<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeClockerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clocker', function (Blueprint $table) {
            $table->dropColumn('time');
            $table->dropColumn('start');

            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->date('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clocker', function (Blueprint $table) {
            $table->dateTime('time');
            $table->boolean('start');

            $table->dropColumn('start');
            $table->dropColumn('end');
            $table->dropColumn('date');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWeekStartAndEndToRooster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rooster', function (Blueprint $table) {
            $table->renameColumn('start', 'start_time');
            $table->renameColumn('end', 'end_time');
            $table->integer('start_week')->default(1);
            $table->integer('end_week')->default(2);
            $table->dropColumn('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rooster', function (Blueprint $table) {
            $table->renameColumn('start_time', 'start');
            $table->renameColumn('end_time', 'end');
            $table->dropColumn('start_week');
            $table->dropColumn('end_week');
            $table->time('date');
        });
    }
}

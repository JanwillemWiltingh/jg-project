<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePlannerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planner', function (Blueprint $table) {
            $table->time('start')->change();
            $table->time('end')->nullable()->change();
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
        Schema::table('planner', function (Blueprint $table) {
            $table->dateTime('start')->change();
            $table->dateTime('end')->change();
            $table->dropColumn('date');
        });
    }
}

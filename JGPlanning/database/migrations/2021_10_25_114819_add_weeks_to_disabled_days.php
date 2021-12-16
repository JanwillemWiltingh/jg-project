<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWeeksToDisabledDays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disabled_days', function (Blueprint $table) {
            $table->integer('start_week');
            $table->integer('end_week');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('disabled_days', function (Blueprint $table) {
            $table->dropColumn('start_week');
            $table->dropColumn('end_week');
        });
    }
}

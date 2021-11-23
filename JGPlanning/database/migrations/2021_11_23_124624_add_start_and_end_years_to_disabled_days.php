<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddStartAndEndYearsToDisabledDays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disabled_days', function (Blueprint $table) {
            $table->year('start_year')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->year('end_year')->default(DB::raw('CURRENT_TIMESTAMP'));
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
            $table->dropColumn('start_year');
            $table->dropColumn('end_year');
        });
    }
}

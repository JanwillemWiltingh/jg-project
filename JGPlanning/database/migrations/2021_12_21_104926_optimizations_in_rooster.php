<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OptimizationsInRooster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rooster', function (Blueprint $table) {
            $table->dropColumn('end_year');
            $table->renameColumn('start_week', 'week');
            $table->dropColumn('end_week');
            $table->renameColumn('weekdays', 'weekday');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
            $table->dropColumn('disabled');
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
            $table->date('end_week')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->renameColumn('year', 'start_year');
            $table->date('end_year')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->renameColumn('weekday', 'weekdays');
            $table->date('created_at');
            $table->date('updated_at');
            $table->boolean('disabled');
        });
    }
}

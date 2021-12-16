<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDataTypesAndDefaultsInRoosters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rooster', function (Blueprint $table) {
            $table->text('start_week')->change();
            $table->text('end_week')->default(52)->change();
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
            $table->integer('start_week')->change();
            $table->integer('end_week')->default(2)->change();
        });
    }
}

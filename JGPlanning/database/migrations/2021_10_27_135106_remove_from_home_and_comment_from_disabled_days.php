<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFromHomeAndCommentFromDisabledDays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disabled_days', function (Blueprint $table) {
            $table->dropColumn('from_home');
            $table->dropColumn('comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home_and_comment_from_disabled_days', function (Blueprint $table) {
            $table->boolean('from_home');
            $table->text('comment');
        });
    }
}

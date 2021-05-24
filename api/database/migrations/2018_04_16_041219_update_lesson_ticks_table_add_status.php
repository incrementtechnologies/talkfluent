<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLessonTicksTableAddStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
  /*      Schema::table('lesson_ticks', function (Blueprint $table) {
            $table->tinyInteger('flag')->after('account_id')->default(0);
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson_ticks', function (Blueprint $table) {
            //
        });
    }
}

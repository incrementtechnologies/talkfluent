<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableWordPopUp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('word_popups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('word_audio_id')->default(null);
            $table->string('original')->default(null);
            $table->string('translation')->default(null);
            $table->string('audio')->default(null);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('word_popups');
    }
}

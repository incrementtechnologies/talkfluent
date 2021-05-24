<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWordAudiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('word_audios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('word')->nullable();
            $table->string('audio')->nullable();
            $table->string('translation')->nullable();
            $table->string('plain')->nullable();
            $table->longText('accent_text')->nullable();
            $table->string('syllabus')->nullable();
            $table->string('syllabus_audio')->nullable();
            $table->string('pronounciation_title')->nullable();
            $table->string('pronounciation_description')->nullable();
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
        Schema::dropIfExists('word_audios');
    }
}

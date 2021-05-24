<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWordAudiosTableAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('word_audios', function (Blueprint $table) {
            $table->dropColumn(['pronounciation_title', 'pronounciation_description']);
            $table->longText('transcription')->after('syllabus_audio')->nullable();
            $table->longText('classifications')->after('transcription')->nullable();
            $table->longText('pronounciation')->after('classifications')->nullable();
            $table->longText('pronounciation_audio')->after('pronounciation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('word_audios', function (Blueprint $table) {
            //
        });
    }
}

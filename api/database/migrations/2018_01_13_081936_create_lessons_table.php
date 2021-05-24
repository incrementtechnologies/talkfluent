<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('category_lesson_id');
            $table->longText('title')->nullable();
            $table->longText('lesson_audio')->nullable();
            $table->longText('english_audio')->nullable();
            $table->longText('culture_tip')->nullable();
            $table->longText('culture_audio')->nullable();
            $table->longText('grammar_tip')->nullable();
            $table->longText('grammar_audio')->nullable();
            $table->longText('grammar')->nullable();
            $table->longText('grammar_test_content')->nullable();
            $table->longText('grammar_question')->nullable();
            $table->longText('grammary_test_content_audio')->nullable();
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('lesson');
    }
}

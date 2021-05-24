<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UdpateBlogCommentRepliesTableSocialColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_comment_replies', function (Blueprint $table) {
            $table->dropColumn(['account_id']);
            $table->string('name')->after('id');
            $table->string('email')->after('name');
            $table->longText('profile_url')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_comment_replies', function (Blueprint $table) {
            //
        });
    }
}

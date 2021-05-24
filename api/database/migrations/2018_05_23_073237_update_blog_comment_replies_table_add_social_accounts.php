<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBlogCommentRepliesTableAddSocialAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_comment_replies', function (Blueprint $table) {
            $table->string('using')->after('profile_url')->default('facebook');
            $table->string('user')->after('id');
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

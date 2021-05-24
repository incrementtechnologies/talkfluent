<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BlogCommentReply;
class BlogCommentReplyController extends TalkController
{
    function __construct(){
    	$this->model = new BlogCommentReply();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Blog;
use App\BlogComment;
use App\BlogCommentReply;
use App\Account;
use App\AccountProfilePicture;
class BlogController extends TalkController
{
    function __construct(){
      $this->model = new Blog();
    }

    public function retrieveCustom(Request $request){
      $data = $request->all();
      $this->model = new Blog();
      $result = $this->retrieveDB($data);

      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $comments = BlogComment::where('blog_id', '=', $result[$i]['id'])->orderBy('created_at', 'DESC')->get();
          // Get Account Information
          if(sizeof($comments) > 0){
            $j = 0;
            foreach ($comments as $keyComment) {
              $accountComment = Account::where('id', '=', $comments[$j]['account_id'])->get();
              $accountProfileComment = AccountProfilePicture::where('id', '=', $comments[$j]['account_id'])->get();
              $replies = BlogCommentReply::where('blog_comment_id', '=', $comments[$j]['id'])->orderBy('created_at', 'ASC')->get();
              // Get Account Information
              $k = 0;
              foreach ($replies as $keyReply) {
                $accountReply = Account::where('id', '=', $replies[$k]['account_id'])->get();
                $accountProfileReply = AccountProfilePicture::where('id', '=', $comments[$j]['account_id'])->get();
                $replies[$k]['account'] = (sizeof($accountReply) > 0) ? $accountReply : null;
                $replies[$k]['profile'] = (sizeof($accountProfileReply) > 0) ? $accountProfileReply : null;
                $k++;
              }
              $comments[$j]['profile'] = (sizeof($accountProfileComment) > 0) ? $accountProfileComment : null;
              $comments[$j]['account'] = (sizeof($accountComment) > 0) ? $accountComment : null;
              $comments[$j]['replies'] = (sizeof($replies) > 0) ? $replies : null;
              $j++;
            }
            $result[$i]['comments'] = $comments;
          }else{
            $result[$i]['comments'] = null;
          }
          $i++;
        }
      }else{
        $result = null;
      }
      return $result;
    }
}

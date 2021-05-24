<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BlogComment;
use App\Blog;
use Carbon\Carbon;
class BlogCommentController extends TalkController
{
    function __construct(){
    	$this->model = new BlogComment();
    }

    public function create(Request $request){
      $data = $request->all();
      $data['status'] = 'APPROVED';
      $this->model = new BlogComment();
      $this->insertDB($data);
      return $this->response();
    }

    public function retrieveByNumberOfComments(Request $request){
      $result = BlogComment::select('blog_id', \DB::raw('count(blog_id) as total'))->groupBy('blog_id')->orderBy('total', 'DESC')->get();

      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $blog = Blog::where('id', '=', $result[$i]['blog_id'])->get();
          $result[$i]['blog'] = (sizeof($blog) > 0) ? $blog[0] : null;
          $i++;
        }
      }else{
        //
      }
      $this->response['data'] = $result;
      $this->response['request_timestamp'] = Carbon::now();
      return $this->response();
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class BlogComment extends APIModel
{
    protected $table = 'blog_comments';
    protected $fillable = ['account_id', 'comment', 'email', 'profile', 'name'];
    protected $dates = ['created_at', 'updated_at'];
    protected $dateFormat = 'Y-m-d H:i:s';
    public function getCreatedAtAttribute($date)
    {
      return Carbon::createFromFormat('Y-m-d H:i:s', $date)->copy()->tz('Asia/Manila')->format('F j, Y g:i A');
    }
}

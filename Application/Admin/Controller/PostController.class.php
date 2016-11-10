<?php  
namespace Admin\Controller;
use Think\Controller;

class PostController extends AdminController
{
    public function index()
    {
        echo 'Post';
    }
  
  	// 帖子回收站
  	public function recycle()
    {
        echo 'Post Recycle';
    }
}
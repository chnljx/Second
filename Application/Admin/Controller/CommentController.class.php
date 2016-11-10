<?php
namespace Admin\Controller;
use Think\Controller;
class CommentController extends AdminController 
{
    public function index()
    {
        $this->display();
    }

    // 回复
    public function reply()
    {
        $this->display();
    }
}
<?php
namespace Admin\Controller;
use Think\Controller;
class CommentController extends AdminController 
{
    public function index()
    {
        $this->assign('title','评论管理');
        $this->assign('part','评论列表');
        $this->display();
    }

    // 回复
    public function reply()
    {
        $this->assign('title','评论管理');
        $this->assign('part','回复列表');
        $this->display();
    }
}
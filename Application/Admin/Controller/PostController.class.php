<?php  
namespace Admin\Controller;
use Think\Controller;

class PostController extends AdminController
{
    public function index()
    {
        $this->assign('title','帖子管理');
        $this->assign('part','帖子列表');
        $this->display();
    }
  
  	// 帖子回收站
  	public function recycle()
    {
        $this->assign('title','帖子管理');
        $this->assign('part','帖子回收站');
        $this->display();
    }
}
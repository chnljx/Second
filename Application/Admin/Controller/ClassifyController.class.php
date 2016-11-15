<?php  
namespace Admin\Controller;
use Think\Controller;

class classifyController extends AdminController
{
    public function index()
    {
       	$this->assign('title','贴吧分类管理');
        $this->assign('part','贴吧分类列表');
        $this->display();
    }
  
  	
  	 public function add()
    {
        $this->display();
    }

}


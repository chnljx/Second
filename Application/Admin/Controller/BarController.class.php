<?php  
namespace Admin\Controller;
use Think\Controller;

class BarController extends AdminController
{
    public function index()
    {
        $this->assign('title','贴吧管理');
        $this->assign('part','贴吧列表');
        $this->display();
    }

    public function add()
    {
        $this->display();
    }
  
  	// 创建贴吧请求
  	public function beg()
    {
       	$this->assign('title','贴吧管理');
        $this->assign('part','创建贴吧请求');
        $this->display();
    }
}
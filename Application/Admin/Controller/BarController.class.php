<?php  
namespace Admin\Controller;
use Think\Controller;

/**
* BarController 贴吧操作
*
* @author xiao
* @version 1.0
*/
class BarController extends AdminController
{
    /**
    * 显示贴吧列表
    * @access public        
    * @return void
    */
    public function index()
    {
        $this->assign('title','贴吧管理');
        $this->assign('part','贴吧列表');
        $this->display();
    }

    /**
    * 创建贴吧页面
    * @access public        
    * @return void
    */
    public function add()
    {
        $type = D('Type');
        $list = $type->getAdminCate();
        $this->assign('list',$list);
        $this->display();
    }

    /**
    * 用户创建贴吧请求页面
    * @access public        
    * @return void
    */  
  	public function beg()
    {
       	$this->assign('title','贴吧管理');
        $this->assign('part','创建贴吧请求');
        $this->display();
    }
}
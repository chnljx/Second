<?php  
namespace Admin\Controller;
use Think\Controller;

class SystemController extends AdminController
{
    public function index()
    {
        $this->assign('type_one','生活');
        $this->assign('type_two','娱乐');
        $this->assign('num1',50);
        $this->assign('num2',50);
        $this->display();
    }
}
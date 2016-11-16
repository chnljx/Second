<?php  
namespace Admin\Controller;
use Think\Controller;

/**
* RootController  管理员控制器
*
* @author michael
* @version 1.0
*/

class RootController extends AdminController
{
    public function index()
    {
        $this->display();
    }
}
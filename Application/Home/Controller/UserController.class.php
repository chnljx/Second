<?php 
namespace Home\Controller;
use \Think\Controller;

/**
* UserController 用户资料
*
* @author xiao
* @version 1.0
*/
class UserController extends HomeController
{
    public function index()
    {
        $this->display();
    }

    /*
        个人资料更新
    */ 
    public function update()
    {
        $this->display('User:info-update');
    }

     /*
        设置的首页
    */ 
    public function set()
    {
        $this->display('User:set-index');
    }

    /*
        头像更新
    */ 
    public function headimg()
    {
        $this->display('User:img-update');
    }

    /*
        个人资料列表
    */ 
    public function list()
    {
        $this->display('User:info-list');
    }
}
<?php  
namespace Admin\Controller;
use Think\Controller;

/**
* UserController  用户表控制器
*
* @author xiao
* @version 1.0
*/

class UserController extends AdminController
{
    public function index()
    {
        $this->assign('title','用户管理');
        $this->assign('part','用户列表');
        $this->display();
    }

    public function add()
    {
        $this->display('User:user-add');
    }

    // 用户回收站
    public function recycle()
    {
        $this->assign('title','用户管理');
        $this->assign('part','用户回收站');
        $this->display('User:user-del');
    }

    // 经验值
    public function exp()
    {
        $this->assign('title','用户管理');
        $this->assign('part','经验值');
        $this->display('User:user-exp');
    }

    // 吧主列表
    public function list()
    {
        $this->assign('title','吧主管理');
        $this->assign('part','吧主列表');
        $this->display('User:barboss-index');
    }
  
    // 申请吧主
    public function beg()
    {
        $this->assign('title','吧主管理');
        $this->assign('part','申请吧主');
        $this->display('User:barboss-add');
    }

    // 管理员列表
    public function adminlist()
    {
        $this->assign('title','管理员管理');
        $this->assign('part','管理员列表');
        $this->display('User:admin-index');
    }

    // 管理员添加
    public function adminadd()
    {
        $this->display('User:admin-add');
    }
  
    // 权限设置
    public function limit()
    {
        $this->assign('title','管理员管理');
        $this->assign('part','权限设置');
        $this->display('User:admin-limit');
    }

    //个人信息
    public function info()
    {
        $this->assign('title','个人信息');
        $this->assign('part','个人信息列表');
        $this->display('User:info');
    }

    //修改密码
    public function password()
    {
        $this->assign('title','个人信息');
        $this->assign('part','修改密码');
        $this->display('User:password');
    }

    //修改个人信息
    public function update()
    {
        $this->assign('title','个人信息');
        $this->assign('part','修改个人信息');
        $this->display('User:info-update');
    }
}

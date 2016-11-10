<?php  
namespace Admin\Controller;
use Think\Controller;

class UserController extends AdminController
{
    public function index()
    {
        $this->display();
    }

    public function add()
    {
        $this->display('User:user-add');
    }

    // 用户回收站
    public function recycle()
    {
        $this->display('User:user-del');
    }

    // 等级
    public function grade()
    {
        $this->display('User:user-grade');
    }    

    // 经验值
    public function exp()
    {
        $this->display('User:user-exp');
    }

    // 吧主列表
    public function list()
    {
        $this->display('User:barboss-index');
    }
  
    // 申请吧主
    public function beg()
    {
        $this->display('User:barboss-add');
    }

    // 管理员列表
    public function adminlist()
    {
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
        $this->display('User:admin-limit');
    }
}

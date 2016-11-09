<?php  
namespace Admin\Controller;
use Think\Controller;

class UserController extends AdminController
{
    public function index(){
        $this->display();
    }

    public function add(){
        $this->display('User:user-add');
    }
}

<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends AdminController {
    public function index(){
        if(session('admin_user.name') == 'admin'){
            $limit['0']['name'] = "超级管理员";
        }else{
            $limit = M('role')->field('name')->where('id in'.M('user_role')->field('rid')->where('uid ='.session('admin_user.id'))->buildSql())->select();
        }
        $this->assign('limit',$limit['0']['name']);
        $this->display();
    }

}
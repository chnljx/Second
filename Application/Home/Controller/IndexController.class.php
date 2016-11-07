<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends HomeController {
    public function index(){
        $this->assign('title','首页');
        $this->display();
    }
}

<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends AdminController {
    public function index(){
        $this->display();
    }

    public function welcome(){
        $this->display('Public:welcome');
    }
}
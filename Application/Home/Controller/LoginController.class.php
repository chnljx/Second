<?php
namespace Home\Controller;
use Think\Controller;

class LoginController extends HomeController 
    {
    public function index() {
        $this->display();
    }

    public function reginster() {
    
         $this->display();
    }

    /**
     * 验证码
     * @access public        
     * @return void
     */
    public function getVerify()
    {
        $config = array(
            'length'        => 4,           // 验证码位数
            'fontSize'      => 20,          // 验证码字体大小（像素）
            'imageW'        => 205,         // 验证码宽度
            'imageH'        => 41,          // 验证码高度 
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }
}

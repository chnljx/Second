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
            'length'        => 1,           // 验证码位数
            'fontSize'      => 23,          // 验证码字体大小（像素）
            'imageW'        => 205,         // 验证码宽度
            'imageH'        => 48,          // 验证码高度 
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }


    /**
     * ajax验证
     * @access public        
     * @return void
     */
    public function doLogin()
    {  
      // var_dump($_POST);
        $User=D('Login');
        if (!$User->create()) {
                $this->error($user->getError());
                exit;
            }
        // if (!$User->create()){
        //     // 如果创建失败 表示验证没有通过 输出错误提示信息
        //     if(IS_AJAX){
        //         $this->ajaxReturn($User->getError());
        //     }else{
        //         $this->error($User->getError(), U('Login/index'));
        //     }
        // }


    }
}

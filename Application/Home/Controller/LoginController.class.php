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
        $User = D("Login"); // 实例化User对象
        if (!$User->create()){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            if(IS_AJAX){
                $this->ajaxReturn($User->getError());
            }else{
                $this->error($User->getError(), U('Login/index'));
            }
        }else{
            // 验证通过 可以进行其他数据操作
            if(!IS_AJAX){
                $map = [];
                $map['name|phone|email'] = I('post.phone');
                $map['password'] = md5(I('post.password'));
                $data = $User->where($map)->find();

                // 如果手机号和密码匹配则进入，否则显示错误
                if ($data) {
                    if($data['active']==1) {
                        if($data['state']==1) {
                            $_SESSION['home_user']=$data;        
                            $this->redirect('Index/index');
                        }else {
                            $this->error('账户已被禁用');
                        }
                    }else {
                        $this->error('账号未激活');
                    }
                } else {
                    $this->error('帐号或密码错误');
                }
                
            }
        }
    }


    public function email()
    {
        $this->display();
    }
}

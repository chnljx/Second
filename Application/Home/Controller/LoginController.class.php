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
                // $map['name|phone|email'] = I('post.phone');
                // $map['password'] = md5(I('post.password'));
                $password = md5(I('post.password'));

                // var_dump($map);
                $data = $User->where($map)->find();
                if(empty($data)){
                    $this->error('账户不存在');
                }elseif($data['passwd']!=$password){
                         $this->error('密码错误');
                }else{
                    if($data['active']==1) {
                        if($data['state']==1) {
                            $_SESSION['home_user']=$data;        
                            $this->redirect('Index/index');
                        }else {
                            $this->error('账户已被禁用');
                        }
                    }else {
                        $url = "<a href='http://michael.com/index.php/Home/Register/active?key={$data['validate']}&uid={$data['id']}' target='_blank'>http://michael.com/index.php/Home/Register/active?key={$data['validate']}&uid={$data['id']}</a>";

                        // 邮箱发送激活信息
                        $result = sendMail($data['email'], $data['name'], '阡陌之家在线激活','请点击连接激活帐号：'.$url);
                        $this->error('请登录到您的邮箱激活帐号');
                    }
                }
            }
        }
    }

    // 找回密码
    public function findwd()
    {
        $this->display();
    }
    // 找回密码问题
    public function findwd1()
    {
        $name=I('post.name');
        $phone=I('post.phone');
        // var_dump($name);
        $data=M('user')->where("name='$name' and phone='$phone'")->find();
        if($data){
            if($data['state']==1){
                $this->assign('data',$data);
                $this->display();
            }else{
                $this->error('该账户已被禁用');
            }
        }else{
            $this->error('账户名和手机号不匹配');
        }

    }
    // 执行找回密码
    public function dofindwd()
    {
         $name=I('post.name'); 
         $type=I('post.type');
         // var_dump($name);
         $data=M('user')->where("name='$name'")->find();
         $id=$data['id'];
         $phone=$data['phone'];
         if(!$data){
            die('没有得到此用户');
         } else {
            $newpwd=rand(100000,999999);

            //发邮件或发短信
            switch ($type) {
                case 'email':

                    $msg="您好，新密码是{$newpwd}";
                    $result=sendMail($data['email'],'密码找回',$msg,'在线测试');
                    if($result){
                        $msg='你好，密码已发送到您邮箱,请注意查看';
                    }else{
                        $msg='你好，邮件发送失败，您可以选择手机短信重置密码';

                    }
                    break;
            }

            //邮件或短信发送成功，更新数据库
            if($result){
                $passwd=md5($newpwd);
                $map['passwd']=$passwd;
                $user=M('user')->where("id='$id'")->save($map);
                if($user>0){
                    echo $msg;
                }

            }else{
                echo $msg;
            }

         }

    }




}

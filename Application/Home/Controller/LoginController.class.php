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
                        $url = "<a href='http://localhost/qianmo/index.php/Home/Register/active?key={$data['validate']}&uid={$data['id']}' target='_blank'>点击激活</a>";

                        // 邮箱发送激活信息
                        $result = sendMail($data['email'], $data['name'], '阡陌之家在线激活',$url);
                        $this->error('请登录到您的邮箱激活帐号');
                    }
                }
            }
        }
    }

    // 执行找回密码
    public function dofindwd()
    {
         $id=session('id');
         $data=M('user')->where("id='$id'")->find();
         if(!$data){
            die('没有得到此用户');
         } else {
            $newpwd=rand(100000,999999);

            //发邮件或发
            $type=I('post.type');
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
                    $this->success('你好，密码已发送到您邮箱,请注意查看',U('Login/index'));
                }

            }else{
                echo $msg;
            }

         }

    }
public function sms()
{
    $this->display();
}

    public function psms()
    {
        $map['name'] = I('post.name');
        $map['phone'] =I('post.phone');
        $data=M('user')->where($map)->find();
        // $data = $user->where(array('phone'=>$_POST['phone']))->find();
        
        $phone=I('post.phone');
        if(empty($data)){
            $this->ajaxReturn(false);
        }else{    
            $code = mt_rand(100000,999999);
            session('code',$code);
            session('id',$data['id']);
            $result = $this->sendTemplateSMS($phone,array($code,'5'),'1');
            // $this->ajaxReturn($code);
        }
     
    }

    public function sendTemplateSMS($to,$datas,$tempId)
    {
        include_once("./CCPRestSmsSDK.php");
        $code;
        //主帐号,对应开官网发者主账号下的 ACCOUNT SID
        $accountSid= '8a216da8588b296f015890aecdd6049b';

        //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
        $accountToken= 'fdf7f411e8ed4143a40c0f75b810d664';

        //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
        //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
        $appId='8a216da8588b296f015890aecf4004a2';

        //请求地址
        //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
        //生产环境（用户应用上线使用）：app.cloopen.com
        $serverIP='sandboxapp.cloopen.com';


        //请求端口，生产环境和沙盒环境一致
        $serverPort='8883';

        //REST版本号，在官网文档REST介绍中获得。
        $softVersion='2013-12-26';

         // 初始化REST SDK
         // global $accountSid,$accountToken,$appId,$serverIP,$serverPort,$softVersion;
         $rest = new \Home\Verdor\Sms\REST($serverIP,$serverPort,$softVersion);
         // var_dump($rest);exit;
         $rest->setAccount($accountSid,$accountToken);
         $rest->setAppId($appId);
        
         // 发送模板短信
         // echo "Sending TemplateSMS to $to <br/>";
         $result = $rest->sendTemplateSMS($to,$datas,$tempId);
         // var_dump($result->statusMsg);die;
         return $result;
         // $this->code = $datas;
         // var_dump($result);
         // if($result == NULL) {
         //     echo "result error!";
         //     exit(0);
         //     // break;
         // }
         // if($result->statusCode!=0) {
         //     echo "error code :" . $result->statusCode . "<br>";
         //     echo "error msg :" . $result->statusMsg . "<br>";
         //     //TODO 添加错误处理逻辑
         // }else{
         //     echo "Sendind TemplateSMS success!<br/>";
         //     // 获取返回信息
         //     $smsmessage = $result->TemplateSMS;
         //     echo "dateCreated:".$smsmessage->dateCreated."<br/>";
         //     echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
         //     //TODO 添加成功处理逻辑
         // }
    }

    public function yzm()
    {
        $yzm=I('post.phoneyzm');
        $code=session('code');
        if($yzm==$code){
            $this->ajaxReturn(true);
        }else{
            $this->ajaxReturn(false);
        }
    } 

    public function findwd1()
    {
        $this->display();
    }

}

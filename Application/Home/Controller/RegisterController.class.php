<?php  
namespace Home\Controller;
use Think\Controller;

class RegisterController extends HomeController
{
    public function _initialize()
    {
        if(!empty(session('home_user'))){
            $this->redirect('Index/index');
        }
    }

    public function index()
    {
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
            'fontSize'      => 20,          // 验证码字体大小（像素）
            'imageW'        => 160,         // 验证码宽度
            'imageH'        => 35,          // 验证码高度 
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }

    public function doadd()
    {   
        $User = D("User"); // 实例化User对象
        if (!$User->create()){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            if(IS_AJAX){    
                $this->ajaxReturn($User->getError());
            }else{
                $this->error($User->getError());
            }
        }else{
            // 验证通过 可以进行其他数据操作
            if(!IS_AJAX){
                $id = $User->add();
                if($id){
                    $data['uid'] = $id;
                    $rid = M('role')->field('id')->where(array('name'=>array('eq', '普通用户')))->find();
                    $data['rid'] = $rid['id'];

                    M('user_role')->add($data);

                    $msg = M('User')->field('name,email,validate')->where('id='.$id)->find();

                    $url = "<a href='http://localhost/qianmo/index.php/Home/Register/active?key={$msg['validate']}&uid={$id}' target='_blank'>点击激活</a>";

                    // 邮箱发送激活信息
                    $result = sendMail($msg['email'], $msg['name'], '阡陌之家在线激活', $url);

                    if($result){
                        $this->success("注册成功，请查收邮件，并激活你的帐户", U('Login/index'));
                    }else{
                        $this->error("注册成功，激活邮件发送失败");
                    }
                }else{
                    $this->error('注册失败');
                }
            }
        }
    }

    // 邮箱激活处理
    public function active()
    {
        $id=intval($_GET['uid']);

        //查询该用户的激活信息
        $user = M('User')->where('id='.$id)->find();
        if(!$user){
            $this->error('激活链接无效', U('Index/index'));
        }

        //判断用户是否已激活
        if($user['active']!=0){
            $this->error('此用户不需要激活', U('Login/index'));
        }

        //通过验证
        if($user['validate']==$_GET['key']){
            //将数据库中状态修改为激活
            $row_count = M('user')->where('id='.$id)->save(array('active'=>1));
            if($row_count !== false){
                $this->success('激活成功', U('Login/index'));
            }else{
                $this->error('激活失败', U('Index/index'));
            }
        }
    }
}

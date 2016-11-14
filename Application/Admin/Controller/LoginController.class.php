<?php
namespace Admin\Controller;
use Think\Controller;

/**
* 后台登录操作
*
* @author michael <chnljx@126.com>
* @version 1.0
*/
class LoginController extends AdminController 
{
    /**
    * 进入页面时判断是否已经登录
    * @return void
    */
    public function _initialize()
    {
        if(session('?uid'))
        {
            $this->redirect('Index/index');
        }
    }

    /**
     * 显示登录界面
     * @access public        
     * @return void
     */
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
            'imageW'        => 205,         // 验证码宽度
            'imageH'        => 41,          // 验证码高度 
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
                $map = [];
                $map['name|email'] = I('post.name');
                $map['passwd'] = md5(I('post.passwd'));
                $data = $User->where($map)->find();
                if ($data) {
                    session('uid', $data['id']);
                    session('name', $data['name']);
                    session('loginnum', $data['loginnum']+1);
                    session('lastdate', $data['lastdate']);
                    session('lastip', $data['lastip']);

                    // 登录次数自增
                    $User->where(session('uid'))->setInc('loginnum', 1);
                    // 更新登录时间和IP
                    $last['lastdate'] = time();
                    $last['lastip'] = get_client_ip();
                    $User->where(session('uid'))->save($last);

                    $this->redirect('Index/index');
                } else {
                    $this->error('账号或密码错误');
                }
                
            }
        }
    }
}
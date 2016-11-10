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
    public function get_verify()
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
     * 登录
     * @access public        
     * @return void
     */
    public function dologin()
    {   
        $User = D("User"); // 实例化User对象
        if (!$User->create()){
        // 如果创建失败 表示验证没有通过 输出错误提示信息
            $this->ajaxReturn($User->getError());
        }else{
        // 验证通过 可以进行其他数据操作
        }
    }
}
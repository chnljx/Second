<?php
namespace Admin\Controller;
use Think\Controller;

/**
* 后台退出操作
*
* @author michael <chnljx@126.com>
* @version 1.0
*/
class LogoutController extends Controller 
{
    /**
    * 退出登录
    * @return void
    */
	public function doLogout()
	{
        // session(null);
		session('admin_user', null);
		$this->redirect('Login/index');
	}

    /**
    * 当系统找不到请求的操作名称的时候，系统会尝试定位空操作
    * @param string $name
    * @return void
    */
    public function _empty($name)
    {
        $this->display('Public:404');
    }
}
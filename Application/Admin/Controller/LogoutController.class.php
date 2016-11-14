<?php
namespace Admin\Controller;
use Think\Controller;

/**
* 后台退出操作
*
* @author michael <chnljx@126.com>
* @version 1.0
*/
class LogoutController extends AdminController 
{
	public function doLogout()
	{
		session(null);
		$this->redirect('Login/index');
	}
}
<?php  
namespace Admin\Controller;
use Think\Controller;

/**
* Admin/Controller下所有控制器的基类
*
* @author michael <chnljx@126.com>
* @version 1.0
*/
class AdminController extends Controller
{
    /**
    * 当系统找不到请求的操作名称的时候，系统会尝试定位空操作
    * @param string $name
    * @return void
    */
    public function _empty($name)
    {
        $this->display('Public:404');
    }

    /**
    * 进入页面时判断是否已经登录
    * @return void
    */
    public function _initialize()
    {
    	if(!session('?admin_user'))
    	{
    		$this->redirect('Login/index');
    	}

        // V($_SESSION);

        //权限过滤
        $mname = CONTROLLER_NAME; //获取控制器名
        $aname = ACTION_NAME; //获取方法名

        // echo $mname.'/'.$aname;

        $nodelist = session('admin_user.nodelist'); //获取权限列表

        // V($_SESSION);

        //让超级管理员admin拥有所有权限
        if(session('admin_user.name') != 'admin'){
            //验证操作权限
            if($mname != 'Personal'){
                if(empty($nodelist[$mname]) || !in_array($aname,$nodelist[$mname])){               
                    $this->display('Public:limit');
                    exit;
                }
            }
        }
    }
}

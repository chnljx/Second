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
    */
    public function _empty($name)
    {
        $this->display('Public:404');
    }
}

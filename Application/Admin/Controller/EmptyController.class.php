<?php  
namespace Admin\Controller;
use Think\Controller;

/**
* 当系统找不到请求的控制器名称的时候，系统会尝试定位空控制器
*
* @author michael <chnljx@126.com>
* @version 1.0
*/
class EmptyController extends Controller
{
    /**
     * 当系统找不到请求的操作名称的时候，系统会尝试定位空操作
     * @access public     
     * @param  string $name 操作名称    
     * @return void
     */
    public function _empty($name)
    {
        $this->display('Public:404');
    }
}

<?php 
namespace Home\Controller;
use \Think\Controller;

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
    */
    public function _empty()
    {
        $this->display('Public:404');
    }
}
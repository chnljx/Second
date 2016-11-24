<?php
namespace Home\Controller;
use Think\Controller;

/**
*ReplyController 帖子控制器
* @author xiao
* @version 1.0
*/
class ReplyController extends HomeController
{

    // 添加回复
    public function doAdd()
    {
        session_start();
        if (empty($_SESSION['home_user'])) {
            $this->error('登录后再回复，请先登录！！',U('Login/index'));
            exit;
        }
        if (IS_AJAX) {
            $data = $_POST;
            $reply = D("Reply"); // 实例化User对象
            if (!$reply->create($data)){ 
                $this->error($reply->getError());
            }else{
                $id = $reply->add();
                // 执行添加
                if ($id > 0) {
                    $exp['exp']=10;
                    M('User')->where('id='.$data['uid'])->save($exp);
                    $this->ajaxReturn(true);
                } else {
                    $this->ajaxReturn(false);
                }
            }
        }  
    }
    
}

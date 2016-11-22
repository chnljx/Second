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
        if (IS_AJAX) {
            $data = $_POST;
            $reply = D("Reply"); // 实例化User对象
            if (!$reply->create($data)){ 
                $this->error($reply->getError());
            }else{
                $id = $reply->add();
                // 执行添加
                if ($id > 0) {
                    echo '添加成功';
                }
            }
        }  
    }

    
}

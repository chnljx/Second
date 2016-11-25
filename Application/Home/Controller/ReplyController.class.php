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
        if (empty($_SESSION['home_user'])) {
            $this->error('登录后再回复，请先登录！！',U('Login/index'));
            exit;
        }
        $comment = M('comment')->field('state')->where('id='.I('post.cmtid'))->find();
        if($comment['state'] == 0){
            echo '该评论已被删除，请刷新页面';
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
                    echo '回复失败';
                }
            }
        }  
    }
    
}

<?php  
namespace Home\Controller;
use Think\Controller;

/**
* BarController 贴吧操作
*
* @author xiao
* @version 1.0
*/
class FollowController extends HomeController
{
    /**
    * 帖子取消收藏
    * @access public        
    * @return void
    */
    public function decoll()
    {
        session_start();
        if (empty($_SESSION['home_user'])) {
            $this->error('登录后再发帖，请先登录！！',U('Login/index'));
            exit;
        }
        if (IS_AJAX) {
            $del = M('collection')->where('postid='.I('post.postid').' and selfid='.I('post.selfid'))->delete();
            if ($del == false) {
                $this->ajaxReturn(false);
            } else {
                $this->ajaxReturn(true);
            }
        }
        
    }

    /**
    * 帖子收藏
    * @access public        
    * @return void
    */
    public function coll()
    {
        session_start();
        if (empty($_SESSION['home_user'])) {
            $this->error('登录后再发帖，请先登录！！',U('Login/index'));
            exit;
        }
        if (IS_AJAX) {
            $data['selfid'] = I('post.selfid');
            $data['postid'] = I('post.postid');

            $follow = M('collection')->add($data);
            if ($follow == false) {
                $this->ajaxReturn(false);
            } else {
                $this->ajaxReturn(true);
            }
            
        }
        
    }
}
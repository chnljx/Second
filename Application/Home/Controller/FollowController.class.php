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
    * 贴吧取消关注
    * @access public        
    * @return void
    */
    public function closebtn()
    {
        if (IS_AJAX) {
            $del = M('follow')->where('bid='.I('post.bid').' and uid='.I('post.uid'))->delete();
            if ($del == false) {
                $this->ajaxReturn(false);
            } else {
                $this->ajaxReturn(true);
            }
        }
        
    }

    /**
    * 贴吧关注
    * @access public        
    * @return void
    */
    public function startbtn()
    {
        if (IS_AJAX) {
            $data['uid'] = I('post.uid');
            $data['bid'] = I('post.bid');

            $follow = M('follow')->add($data);
            if ($follow == false) {
                $this->ajaxReturn(false);
            } else {
                $this->ajaxReturn(true);
            }
            
        }
        
    }
}
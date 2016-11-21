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
    public function stop()
    {
        if (IS_AJAX) {
            $del = M('follow')->where('id='.I('post.id'))->delete();
            if ($del == false) {
                echo '取消关注失败';
                exit;
            }
        }
        
    }

    /**
    * 贴吧关注
    * @access public        
    * @return void
    */
    public function start()
    {
        if (IS_AJAX) {
            $data['uid'] = I('post.uid');
            $data['bid'] = I('post.bid');

            $follow = M('follow')->add($data);
            if ($follow == false) {
                echo '关注失败';
                exit;
            }
            
        }
        
    }
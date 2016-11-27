<?php  
namespace Admin\Controller;
use Think\Controller;

class PostController extends AdminController
{

    public function index($state='')
    {
        // 根据帖子状态  查看
        if (!empty(I('get.state'))) {
            // 帖子状态为显示
            if (I('get.state') == 'showbtn') {
                $state = ' and p.state=1';
            } elseif (I('get.state') == 'hidebtn') { //帖子状态为隐藏
                $state = ' and p.state=0';
            }
        }
        $post = M('post')->field('b.name bname, u.name uname, u.picname upic, p.id, p.title, p.ctime, p.state, p.uid, p.bid')->table('qm_post p, qm_bar b, qm_user u')->where('u.id=p.uid and b.id=p.bid'.$state)->select();
        $this->assign('title','帖子管理');
        $this->assign('part','帖子列表');
        $this->assign('post',$post);
        $this->display();
    }

    /*
        查看帖子
    */ 
    public function show()
    {
        $data = M('post')->field('b.picname bpic, u.picname upic, u.name uname, b.name bname, b.descr bdescr, p.title, p.descr, p.ctime, p.state')->table('qm_bar b, qm_user u, qm_post p')->where('b.id=p.bid and u.id=p.uid and p.id='.I('get.id'))->find();
        // $pics = M('post_photo')->field('picname')->where('postid='.I('get.id'))->select();
        $this->assign('data',$data);
        $this->assign('pics',$pics);
        $this->display();
    }


    /**
    * 帖子隐藏
    * @access public        
    * @return void
    */
    public function stop()
    {
        if (IS_AJAX) {
            $data['state'] = 0;
            $user = M('post')->where('id='.I('post.id'))->save($data);
            if ($user == false) {
                $this->ajaxReturn(false);
            } else {
                $this->ajaxReturn(true);

            }
        } else {
            $this->ajaxReturn(false);
        }
        
    }

    /**
    * 帖子显示
    * @access public        
    * @return void
    */
    public function start()
    {
        if (IS_AJAX) {
            $data['state'] = 1;
            $user = M('post')->where('id='.I('post.id'))->save($data);
            if ($user == false) {
                $this->ajaxReturn(false); 
            } else {
                $this->ajaxReturn(true);
            }
        } else {
            $this->ajaxReturn(false);
        }
    }  
}
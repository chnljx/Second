<?php  
namespace Admin\Controller;
use Think\Controller;

class PostController extends AdminController
{
    public function index()
    {
        $post = M('post')->field('b.name bname, u.name uname, u.picname upic, p.id, p.title, p.ctime, p.state, p.uid, p.bid')->table('qm_post p, qm_bar b, qm_user u')->where('u.id=p.uid and b.id=p.bid')->select();
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
        $pics = M('post_photo')->field('picname')->where('postid='.I('get.id'))->select();
        $this->assign('data',$data);
        $this->assign('pics',$pics);
        $this->display();
    }
  
  	// 帖子回收站
  	public function recycle()
    {
        $this->assign('title','帖子管理');
        $this->assign('part','帖子回收站');
        $this->display();
    }
}
<?php
namespace Home\Controller;
use Think\Controller;

/**
*PostController 帖子控制器
* @author xiao
* @version 1.0
*/
class PostController extends HomeController
{
	public function index()
	{
        session_start();
        if (empty(I('get.id'))) {
            $this->display('Public:404');
            exit;
        }

        // 楼主
        $arr = M('post')->field('b.name bname, b.picname bpic, u.name uname, u.picname upic, u.exp, p.uid ,p.bid, p.title, p.descr, p.ctime, p.id')->table('qm_user u, qm_bar b, qm_post p')->where('p.id='.I('get.id').' and u.id=p.uid and b.id=p.bid')->find();
        if (!empty($_SESSION['home_user'])) {
            $user = M('follow')->where('bid='.$arr['bid'].' and uid='.$_SESSION['home_user']['id'])->find();
            $coll = M('collection')->where('postid='.I('get.id').' and selfid='.$_SESSION['home_user']['id'])->find();
            
        }
        // 分页
        $arr['p']=$_GET['p'];
        // 关注人数
        $follow = M('follow')->where('bid='.$arr['bid'])->count();
        // 帖子数
        $post = M('post')->where('bid='.$arr['bid'])->count();
        // 评论
        $comment = M('comment')->field('u.name name, u.picname upic, u.exp, c.uid, c.ctime, c.content,c.id')->table('qm_user u, qm_comment c')->where('c.postid='.I('get.id').' and c.uid=u.id and c.state=1')->page($_GET['p'],5)->select();

        // 分页
        $count = M('comment')->table('qm_user u, qm_comment c')->where('c.postid='.I('get.id').' and c.uid=u.id and c.state=1')->count();

         // 贴吧名人
        $supfans = M('follow')->field('u.name, u.exp, u.picname')->table('qm_user u, qm_follow f')->where('f.bid='.$arr['bid'].' and f.uid=u.id')->order('u.exp desc')->limit(3)->select();

        // 回复
        foreach ($comment as $k => $v) {
            $comment[$k]['r'] = M('reply')->field('u.name, u.picname, r.content')->table('qm_user u,qm_reply r')->where('r.cmtid='.$v['id'].' and r.uid=u.id')->select();
        }
        // dump($comment);
        $Page = new \Think\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);
        $this->assign('user',$user);
        $this->assign('coll',$coll);
        $this->assign('supfans',$supfans);
        $this->assign('comment',$comment);
        $this->assign('follow',$follow);
        $this->assign('post',$post);
        $this->assign('arr',$arr);
        $this->assign('array',$array);
		$this->display();
	}

    // 添加帖子
    public function doAdd()
    {
        session_start();
        if (empty($_SESSION['home_user'])) {
            $this->error('登录后再发帖，请先登录！！',U('Login/index'));
            exit;
        }
        $data = $_POST;
        
        $post = D("Post"); // 实例化User对象
        if (!$post->create($data)){ 
            $this->error($post->getError());
        }else{

            // 执行添加
            if ($post->add() > 0) {
                $this->success('添加成功', U('Bar/index',array('id'=>$data['bid'])));
            } else {
                $this->error('添加失败');
            }   
        }
    }

    
}

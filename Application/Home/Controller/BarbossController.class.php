<?php 
namespace Home\Controller;
use \Think\Controller;

/**
* BarbossController 用户资料
*
* @author michael
* @version 1.0
*/
class BarbossController extends HomeController
{
    public function _initialize()
    {
        date_default_timezone_set('PRC');
        if(empty(session('home_user'))){
            $this->error('请先登录', U('Login/index'));
        }
        if (empty(I('get.id'))) {
            $this->display('Public:404');
            exit;
        }

        $boss = M('bar')->where('id='.I('get.id').' and uid='.session('home_user.id'))->find();
        if ($boss == '') {
            $this->error('权限不足', U('Index/index'));
        }
        $bar = M('bar')->field('state, begstate')->where('id='.I('get.id'))->find();
        if($bar['state'] == 0){
            $this->error('该吧已被禁用', U('Index/index'));
        } else if ($bar['begstate'] == 1 || $bar['begstate'] == 3) {
            $this->error('该吧未创建', U('Index/index'));
        }
    }

    public function index()
    {   
        $bar = M('bar')->where('id='.I('get.id'))->find();
        
        // $post_count = M('Post')->where('uid='.session('home_user.id'))->count("id");

        $follow = M('Follow')->field('u.name, u.picname, u.exp')->table('qm_follow f, qm_user u')->where('f.bid='.I('get.id').' and f.uid=u.id and u.state=1')->order('u.exp desc')->select();


        // session('home_user.post_count', $post_count);

        // $this->assign('post_count', $post_count);

        $this->assign('bar', $bar);
        $this->assign('follow', $follow);
        $this->display();
    }



    public function postview()
    {
        $bid=I('get.id');
        $bar=M('bar')->where("id='$bid'")->find();
        // $list = M('Post')->where("bid='$bid' and state=1")->select();
        $list=M('')->table('qm_post p,qm_user u')->field('p.id,p.title,p.descr,p.ctime,u.name')->where("p.uid=u.id and p.bid='$bid' and p.state=1")->select();
        // var_dump($list);die;
        $count = M('Post')->where("bid='$bid' and state=1")->count('id');// 查询满足要求的总记录数
        $this->assign('bar',$bar);
        $this->assign('list',$list);
        $this->assign('count',$count);
        $this->display('Barboss:post');
    }

    public function hind()
    {
        $postid=I('get.postid');
        // var_dump($postid);
        $map['state']=0;
        $data=M('post')->where("id='$postid'")->save($map);
        if($data>0){
            $this->success("删除成功");
        }else{
            $this->error("删除失败");
        }

    }


    // 显示帖子完整内容
    public function show()
    {
        $data=M('post')->where('id='.I('get.pid'))->find();

        // var_dump($pic);
        $this->assign('data', $data);
        $this->display('Barboss/content-show');
    }
    
}
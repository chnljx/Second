<?php
namespace Admin\Controller;
use Think\Controller;
class CommentController extends AdminController 
{
    public function index()
    {
        $this->assign('title', '评论管理');
        $this->assign('part', '评论列表');

        $data = M('Comment')->select();
        $this->assign('list', $data);
        $this->assign('num', count($data));
        $this->display();
    }

    // 禁用评论操作
    public function memberStop()
    {
        $data['state'] = 0;
        $mes=M('Comment')->where('id='.I('post.id'))->save($data);
        if(!$mes) {
            echo "停用失败";
            exit;
        }
    }

    // 启用评论操作
    public function memberStart()
    {   
        $data['state'] = 1;
        $mes = M('Comment')->where('id='.I('post.id'))->save($data);
        if(!$mes){
            echo "启用失败";
            exit;
        }
    }

    // 显示用户信息
    public function userShow()
    {
        $data = M('User')->where('id='.I('get.id'))->find();
        $this->assign('list', $data);
        $this->display('User/member-show');
    }

    // 显示评论完整内容
    public function contentShow()
    {
        $user=M('User')->field('name,picname')->where('id='.I('get.uid'))->find();
        $data = M('Comment')->field('content,ctime')->where('id='.I('get.id'))->find();
        $pic = M('comment_photo')->field('picname')->where('cmtid='.I('get.id'))->select();

        // var_dump($pic);
        $this->assign('user', $user);
        $this->assign('list', $data);
        $this->assign('pic', $pic);
        $this->display('content-show');
    }

    // 显示回复完整内容
    public function replyShow()
    {
        $user=M('User')->field('name,picname')->where('id='.I('get.uid'))->find();
        $data = M('replay')->field('content,ctime')->where('id='.I('get.id'))->find();
        $this->assign('user', $user);
        $this->assign('list', $data);
        $this->display('replay-show');
    }



    // 回复
    public function reply()
    {
        $data=M('replay')->select();
        $this->assign('list',$data);
        $this->assign('title','评论管理');
        $this->assign('part','回复列表');
        $this->assign('num',count($data));
        $this->display();
    }

    // 禁用回复操作
    public function replayStop()
    {
        $data['state'] = 0;
        $mes=M('replay')->where('id='.I('post.id'))->save($data);
        if(!$mes) {
            echo "停用失败";
            exit;
        }
    }

    // 启用回复操作
    public function replayStart()
    {   
        $data['state'] = 1;
        $mes = M('replay')->where('id='.I('post.id'))->save($data);
        if(!$mes){
            echo "启用失败";
            exit;
        }
    }
}
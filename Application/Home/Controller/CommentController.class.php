<?php
namespace Home\Controller;
use Think\Controller;

/**
*CommentController 评论控制器
* @author xiao
* @version 1.0
*/
class CommentController extends HomeController
{

    // 添加评论
    public function doAdd()
    {
        if (empty($_SESSION['home_user'])) {
            $this->error('登录后再评论，请先登录！！',U('Login/index'));
            exit;
        }
        $post = M('post')->field('state,bid')->where('id='.I('post.postid'))->find();
        if($post['state'] == 0){
            $this->error('该帖已被删除',U('Bar/index',array('id'=>$post['bid'])));
            exit;
        }
        $bar = M('bar')->field('state')->where('id='.$post['bid'])->find();
        if($bar['state'] == 0){
            $this->error('该吧已被禁用', U('Index/index'));
            exit;
        }
        $data = $_POST;
        $post = D("Comment"); // 实例化User对象
        if (!$post->create($data)){ 
            $this->error($post->getError());
        }else{

            // 执行添加
            if ($post->add() > 0) {
                $exps = M('User')->where('id='.$data['uid'])->find();
                $exp['exp']=50+$exps['exp'];
                M('User')->where('id='.$data['uid'])->save($exp);
                $this->success('添加成功', U('Post/index',array('id'=>$data['postid'])));
            } else {
                $this->error('添加失败');
            }   
        }
    }
}

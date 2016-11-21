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
        $arr = M('post')->field('b.name bname, b.picname bpic, u.name uname, u.picname upic, u.exp, p.title, p.descr, p.ctime, p.id')->table('qm_user u, qm_bar b, qm_post p')->where('p.id='.I('get.id').' and u.id=p.uid and b.id=p.bid')->find();
        // 评论
        $comment = M('comment')->field('u.name name, u.picname upic, u.exp, u.id uid, c.ctime, c.content')->table('qm_user u, qm_comment c')->where('c.postid='.I('get.id').' and c.uid=u.id and c.state=1')->page($_GET['p'],5)->select();
        // 分页
        // $count = M('comment')->field('u.name name, u.picname upic, u.id uid, c.ctime, c.content')->table('qm_user u, qm_comment c')->where('c.postid='.I('get.id').' and c.uid=u.id and c.state=1')->select();

        // $Page = new \Think\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数
        // $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);
        $this->assign('comment',$comment);
        $this->assign('arr',$arr);
		$this->display();
	}

    // 添加帖子
    public function doAdd()
    {
        $data = $_POST;
        $post = D("Post"); // 实例化User对象
        if (!$post->create($data)){ 
            $this->error($post->getError());
        }else{

            // 执行添加
            if ($post->add() > 0) {
                $this->success('添加成功', U('Bar/index'));
            } else {
                $this->error('添加失败');
            }   
        }
    }

    // public function upload() 
    // {
        /*$config = array(
            'maxSize' => 3145728,
            'rootPath' => './Upload/img/post/',
            'saveName' => array('uniqid',''),
            'exts' => array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub' => true,
            'subName' => array('date','Ymd'),
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        // 上传单个文件
        $info = $upload->uploadOne($_FILES['picname']);
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        } else {// 上传成功 获取上传文件信息
            $path = $info['savepath'].$info['savename'];
            $image = new \Think\Image();
            $image->open("./Upload/img/post/".$path);
            // 按照原图的比例生成一个最大为90*90的缩略图并保存为thumb.jpg
            $path = time().$info['savename'];
            $image->thumb(100, 100)->save('./Upload/img/post-thumb/'.$path);
        }*/
    //     $data = [
    //         'code' => 0,
    //         'msg' => '1',
    //         'data' => ['src' => '1','title' => '1'],
    //     ];
    //     $this->ajaxReturn($data);
    // }
}

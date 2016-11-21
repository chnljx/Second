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
        $data = $_POST;
        $post = D("Comment"); // 实例化User对象
        if (!$post->create($data)){ 
            $this->error($post->getError());
        }else{

            // 执行添加
            if ($post->add() > 0) {
                $this->success('添加成功', U('Post/index'));
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

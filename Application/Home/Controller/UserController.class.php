<?php 
namespace Home\Controller;
use \Think\Controller;

/**
* UserController 用户资料
*
* @author xiao
* @version 1.0
*/
class UserController extends HomeController
{
    public function _initialize()
    {
        date_default_timezone_set('PRC');
        if(empty(session('home_user'))){
            $this->error('请先登录', U('Login/index'));
        }
    }

    public function index()
    {   
        $this->display();
    }

    /*
        个人资料更新
    */ 
    public function editview()
    {
        $User = M('User')->where('id='.session('home_user.id'))->find();
        // V($User);
        $this->assign('user',$User);
        $this->display('User:edit');
    }

    public function save()
    {
        $User = D("User"); // 实例化User对象
        if (!$User->create()){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            $this->error($User->getError());
        }else{
            // 验证通过 可以进行其他数据操作
            $id = I('post.id');
            $map['sex'] = I('post.sex');
            $map['code'] = I('post.code');
            $map['birthday'] = strtotime(I('post.birthday'));
            $map['descr'] = I('post.descr');
            if($User->where("id=".$id)->save($map)){
                $this->success('修改成功');
            }else{
                $this->error('修改失败');
            }
        }
    }

    // 密码显示
    public function pwdview()
    {
        $this->display('User:pwd');
    }

    // 密码修改操作
    public function pwdsave()
    {
        if(md5(I('post.prepasswd')) == session('home_user.passwd')){
            if(I('post.passwd') != I('post.confirmpasswd')){
                $this->error('两次密码输入不一致');
            }

            $map['passwd'] = md5(I('post.passwd'));
            M('User')->where('id='.session('home_user.id'))->save($map);
            session(null);
            $this->success("密码修改成功", U('Login/index'));
        }else{  
            $this->error('原密码错误');
        }
    }

    /*
        头像页
    */ 
    public function headview()
    {
        $this->display('User:head');
    }

    // 头像更新操作
    public function headsave()
    {
        if(!empty($_FILES['picname']['tmp_name'])){
                $config = array(
                    'maxSize' => 5242880,
                    'rootPath' => './Upload/img/avatar/',
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
                }else{// 上传成功 获取上传文件信息
                    $path = $info['savepath'].$info['savename'];
                    $image = new \Think\Image();
                    $image->open("./Upload/img/avatar/".$path);
                    $path = time().$info['savename'];
                    $image->thumb(90, 90)->save('./Upload/img/avatar-thumb/'.$path);
                    session('home_user.picname', $path);
                    $this->success('上传头像成功');
                }
        }else{
            $this->error('请选择图片');
        }
    }

    /*
        个人资料列表
    */ 
    public function list()
    {
        $this->display('User:info-list');
    }
}
<?php
namespace Admin\Controller;
use Think\Controller;

/**
* 个人信息操作
*
* @author michael <chnljx@126.com>
* @version 1.0
*/
class PersonalController extends AdminController 
{
    public function index()
    {   
        $data = M('User')->where('id='.session('admin_user.id'))->find();
        $this->assign('list', $data);
        $this->display();
    }

    public function editview()
    {
        $data = M('User')->where('id='.session('admin_user.id'))->find();
        $this->assign('list', $data);
        $this->display('Personal:edit');
    }

    // 用户信息修改操作
    public function save()
    {
        $User = D("User"); // 实例化User对象
        if (!$User->create()){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            // if(IS_AJAX){    
                // $this->ajaxReturn($User->getError());
            // }else{
                $this->error($User->getError());
            // }
        }else{
            // 验证通过 可以进行其他数据操作
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
                }
            }
            
            if($path != ''){
                $map['picname'] = $path;
            }

            if($_POST['birthday'] != ''){
                $map['birthday'] = strtotime($_POST['birthday']);
            }

            $map['sex'] = $_POST['sex'];
            $map['email'] = $_POST['email'];
            $map['phone'] = $_POST['phone'];
            $map['code'] = $_POST['code'];
            $map['descr'] = $_POST['descr'];
            $User->where('id='.$_POST['id'])->save($map); // 根据条件更新记录
            $this->success('修改成功');
        }
    }

    public function editpwdview()
    {
        $this->display('Personal:pwd');
    }

    public function pwdsave()
    {
        
        if(!IS_POST) {
            $this->error('非法数据传递');
        }

        $data['passwd'] = I('post.passwd');
        $data['repasswd'] = I('post.repasswd');

        if ($data['passwd'] == $data['repasswd']){
            $data['passwd'] = md5($data['passwd']);
            M('User')->where('id='.session('admin_user.id'))->field('passwd')->filter('strip_tags')->save($data);
            $this->success('密码修改成功');
        } else {
            $this->error('两次密码输入不一致');
        }
    }
}
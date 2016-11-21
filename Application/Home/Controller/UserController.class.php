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
    public function update()
    {
        $User = M('User')->where('id='.session('home_user.id'))->find();
        // V($User);
        $this->assign('user',$User);
        $this->assign('title','基本资料');
        $this->display('User:info-update');
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

    /*
        头像更新
    */ 
    public function headimg()
    {
        $this->assign('title','头像设置');
        $this->display('User:img-update');
    }

    /*
        个人资料列表
    */ 
    public function list()
    {
        $this->display('User:info-list');
    }
}
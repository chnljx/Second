<?php  
namespace Admin\Controller;
use Think\Controller;

/**
* UserController  用户表控制器
*
* @author michael
* @version 1.0
*/

class UserController extends AdminController
{
    public function index()
    {
        $this->assign('title', '用户管理');
        $this->assign('part', '用户列表');

        $data = M('User')->where('id in'.M('user_role')->field('uid')->where('rid = 3')->buildSql())->select();
        // V(count($data));exit;
        $this->assign('list', $data);
        $this->assign('num', count($data));
        $this->display();
    }

    public function add()
    {
        $this->display('User:user-add');
    }

    public function addAction()
    {   
        $User = D("UserAdd"); // 实例化User对象
        if (!$User->create()){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            if(IS_AJAX){    
                $this->ajaxReturn($User->getError());
            }else{
                $this->error($User->getError());
            }
        }else{
            // 验证通过 可以进行其他数据操作
            if(!IS_AJAX){
                $id = $User->add();
                if($id){
                    $data['uid'] = $id;
                    $data['rid'] = 3;
                    M('user_role')->add($data);
                    $this->success('添加成功');
                }else{
                    $this->error('添加失败');
                }
            }
        }
    }

    // 经验值
    public function exp()
    {
        $this->assign('title','用户管理');
        $this->assign('part','等级列表');
        $this->display('User:user-exp');
    }

    // 吧主列表
    public function list()
    {
        $this->assign('title','用户管理');
        $this->assign('part','吧主列表');
        $this->display('User:barboss-index');
    }

    // 管理员列表
    public function adminlist()
    {
        $this->assign('title','管理员管理');
        $this->assign('part','管理员列表');
        $this->display('User:admin-index');
    }

    // 管理员添加
    public function adminadd()
    {
        $this->display('User:admin-add');
    }
  
    // 权限设置
    public function limit()
    {
        $this->assign('title','管理员管理');
        $this->assign('part','权限设置');
        $this->display('User:admin-limit');
    }

    //个人信息
    public function info()
    {
        $this->assign('title','个人信息');
        $this->assign('part','个人信息列表');
        $this->display('User:info');
    }

    //修改密码
    public function password()
    {
        $this->assign('title','个人信息');
        $this->assign('part','修改密码');
        $this->display('User:password');
    }

    //修改个人信息
    public function update()
    {
        $this->assign('title','个人信息');
        $this->assign('part','修改个人信息');
        $this->display('User:info-update');
    }
}

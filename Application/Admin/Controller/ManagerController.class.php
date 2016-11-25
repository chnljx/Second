<?php  
namespace Admin\Controller;
use Think\Controller;

/**
* UserController  用户表控制器
*
* @author michael
* @version 1.0
*/

class ManagerController extends AdminController
{
    private $roleName = array('name'=>'');  // 角色名称

    public function index()
    {
        $this->roleName['name'] = array(array('neq', '吧主'), array('neq', '普通用户'), 'and');
        $data = M('User')->where('id in'.M('user_role')->field('uid')->where('rid in'.M('role')->field('id')->where($this->roleName)->buildSql())->buildSql())->select();
        $this->assign('list', $data);
        $this->assign('num', count($data)-1);
        $this->display();
    }

    public function addview()
    {   
        $this->roleName['name'] = array(array('neq', '超级管理员'), array('neq', '吧主'), array('neq', '普通用户'), 'and');
        $role = M('role')->where($this->roleName)->select();
        $this->assign('role', $role);
        $this->display('Manager:add');
    }

    public function doadd()
    {
        $User = D("User"); // 实例化User对象
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
                // V($_POST);exit;
                $id = $User->add();
                if($id){
                    $data['uid'] = $id;
                    $data['rid'] = I('post.rid');

                    M('user_role')->add($data);
                    $this->success('添加成功');
                }else{
                    $this->error('添加失败');
                }
            }
        }
    }

    public function editview()
    {
    	$uid = I('get.id');

        // 显示用户信息
        $User = M('User')->where('id='.$uid)->find();
        $this->assign('list', $User);

        // 显示角色信息
        $this->roleName['name'] = array(array('neq', '超级管理员'), array('neq', '吧主'), array('neq', '普通用户'), 'and');
        $role = M('role')->where($this->roleName)->select();
        $this->assign('role', $role);

        // 用户角色rid
        $user_role = M('user_role')->field('rid')->where("uid=".$uid)->find();
        $this->assign('user_role', $user_role);

        $this->display('Manager:edit');
    }

}
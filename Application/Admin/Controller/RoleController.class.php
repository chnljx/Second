<?php  
namespace Admin\Controller;
use Think\Controller;

/**
* RootController  管理员控制器
*
* @author michael
* @version 1.0
*/

class RoleController extends AdminController
{
    public function index()
    {   
        $role = M('role')->select();
        // V($role);exit;
        $this->assign('list',$role);
        $this->assign('num',count($role));
        $this->display();
    }

    /**
    * 删除角色操作
    */
    public function delete()
    {
        $role = M('role')->delete(I('post.id'));
        if($role != '0'){
            echo '删除成功';
        }
    }

    /**
    * 角色添加显示页面
    */
    public function add()
    {
        $this->display();
    }

    /**
    * 角色添加操作
    */
    public function doadd()
    {
        $Role = D("Role"); // 实例化User对象
        if (!$Role->create()){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            if(IS_AJAX){    
                $this->ajaxReturn($Role->getError());
            }else{
                $this->error($Role->getError());
            }
        }else{
            // 验证通过 可以进行其他数据操作
            if(!IS_AJAX){
                $map['name'] = I('post.name');
                $map['remark'] = I('post.remark');
                if($Role->add($map)){
                    $this->success('添加成功');
                }else{
                    $this->error('添加失败');
                }
            }
        }
    }

    /**
    * 角色修改显示页面
    */
    public function edit()
    {   
        //查找该角色信息
        $role = M('role')->where(array('id'=>array('eq',I('id'))))->find();

        //查找所有的节点
        $nodes = M('node')->select();

        //获取该角色的权限
        $ro_node = M('role_node')->where(array('rid'=>array('eq',$role['id'])))->select();
        $ro_nodes = array();
        //遍历重组数组
        foreach($ro_node as $v){
            $ro_nodes[] = $v['nid'];
        }
        
        //向模板分配该用户拥有的权限信息
        $this->assign('ro_nodes',$ro_nodes);
        //向模板分配节点信息
        $this->assign('nodes',$nodes);
        //角色信息
        $this->assign('role',$role);

        $this->display();
    }

    /**
    * 角色修改操作
    */
    public function save()
    {
        $rid = $_POST['id'];

        M('role')->where('id='.$rid)->save(array('remark'=>I('post.remark')));

        //删除该 角色的 所有信息--避免重复添加
        M('role_node')->where(array('rid'=>array('eq',$rid)))->delete();

        //循环添加
        foreach($_POST['node'] as $v){
            $data['nid'] = $v;
            $data['rid'] = $rid;
            //执行添加
            M('role_node')->data($data)->add();
        }

        $this->success('修改成功');
    }
}
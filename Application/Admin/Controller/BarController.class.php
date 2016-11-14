<?php  
namespace Admin\Controller;
use Think\Controller;

/**
* BarController 贴吧操作
*
* @author xiao
* @version 1.0
*/
class BarController extends AdminController
{
    /**
    * 显示贴吧列表
    * @access public        
    * @return void
    */
    public function index()
    {
        $user = M('bar');
        $user = $user->field('u.name uname, t.name tname, b.id, b.name, b.descr, b.picname, b.ctime, b.state')->table('qm_bar b, qm_type t, qm_user u')->where('b.typeid=t.id and b.uid=u.id')->select();
        $this->assign('title','贴吧管理');
        $this->assign('part','贴吧列表');
        $this->assign('user',$user);
        $this->display();
    }

    /**
    * 创建贴吧页面
    * @access public        
    * @return void
    */
    public function add()
    {
        $type = D('Type');
        $list = $type->getAdminCate();
        $this->assign('list',$list);
        $this->display();
    }

    /**
    * 执行创建贴吧
    * @access public        
    * @return void
    */
    public function doadd()
    {
        var_dump($_POST);
        //得到数据模型
        $bar = M('bar');
        //过滤数据,数据验证
        if (!$bar->create()) {
            if(!IS_AJAX){
                $this->ajaxReturn($bar->getError());
            }else{
                //如果创建数据失败,表示验证没有通过
                //输出错误信息 并且跳转
                 $this->error($bar->getError()); 
            }      
        } else {
            
            // 验证通过 执行添加操作
            // 执行添加
            if (M('bar')->add() > 0) {
                $this->success('添加成功', U('index'));
            } else {
                $this->error('添加失败');
            }

        }
    }


    /**
    * 用户创建贴吧请求页面
    * @access public        
    * @return void
    */  
  	public function beg()
    {
       	$this->assign('title','贴吧管理');
        $this->assign('part','创建贴吧请求');
        $this->display();
    }
}
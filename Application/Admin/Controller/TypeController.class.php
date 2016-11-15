<?php  
namespace Admin\Controller;
use Think\Controller;

class TypeController extends AdminController
{
    public function index()
    {
      //实例化
      $type=D('Type');
      //获取分类信息
      $list=$type->getAdminCate();
      $this->assign('title','贴吧分类管理');
      $this->assign('part', '贴吧分类列表');
      $this->assign('list',$list);
      $this->display();
    }

    public function add()
    {
      $this->assign('title','贴吧分类管理');
      $this->assign('part', '添加分类');
      $this->display();
    }
  
  	//加载添加页面
    //执行添加信息
    public function doadd(){
    //实例化
    $type = D('Type');
    //初始化数据
    if(!$type->create()){
      $this->error($this->_model->getError());
      exit;
    };
    //执行添加
    if($type->add()>0){
      $this->success("添加成功！",U('Type/index'));
    }else{
      $this->error("添加失败");
    }
  }


    /**
      *分类树显示
    */
    public function treeList(){
      //实例化
      $type=D('Type');
      //获取分类管理
      $list=$type->getHomeCate();
      $this->assign('list',$list);
      $this->assign('title','贴吧分类管理');
      $this->assign('part', '贴吧分类树');

      $this->display();
    }

    //删除
    public function del()
    {
      //实例化
      $type=D('Type');
      //拼装删除条件
      $map['id']=array('eq',I('id'));
      $map['path']=array('like','%'.I('id').'%');
      $map['_logic']='or';
      if($type->where($map)->delete()>0){
        $this->success("删除成功！",U('Type/index'));
      }else{
        $this->error("删除失败");
      }
    }

}


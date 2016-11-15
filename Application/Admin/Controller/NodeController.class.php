<?php 
	namespace Admin\Controller;
	
	class NodeController extends AdminController{
		private $_model = null; //数据库操作类

		//初始化操作
		public function _initialize(){
			parent::_initialize();
			$this->_model = D('Node');
		}

		//列表详情
		public function index(){
			//查询数据
			$list = $this->_model->select();

			//分配变量
			$this->assign("list",$list);
			$this->assign('title','节点管理');
      		$this->assign('part', '节点列表');
      		$this->assign('num', count($list));
			//加载模板
			$this->display();
		}

		public function add(){
			$this->assign('title','节点管理');
      		$this->assign('part', '添加节点');
			$this->display();
		}

		//执行添加操作
		public function doadd(){

			if(!$this->_model->create()){
				$this->error($this->_model->getError());
				exit;
			}

			if($this->_model->add() > 0){
				$this->success("添加成功！",U('Node/add'));
			}else{
				$this->error("添加失败！");
			}
		}

		//删除操作
		public function del(){


			//实例化
			  $type=D('node');
			  //拼装删除条件
			  $map['id']=array('eq',I('id'));
			  if($type->where($map)->delete()>0){
			    $this->success("删除成功！",U('Node/index'));
			  }else{
			    $this->error("删除失败");
			  }
			
		}

		//加载修改页面c 
		public function edit(){
			//查出数据
			$vo = $this->_model->where(array('id'=>array('eq',I('id'))))->find();
			//向模板分配数据
			$this->assign('vo',$vo);
			$this->assign('title','节点管理');
      		$this->assign('part', '修改节点');
			//加载模板
			$this->display();
		}

		//执行修改操作
		public function save(){
			// 初始化
			// if(!$this->_model->create()){
			// 	$this->error($this->_model->getError());
			// 	exit;
			// }
			// //执行修改 
			// if($this->_model->save() >= 0){
			// 	$this->success("修改成功！",U('Node/index'));
			// }else{
			// 	$this->error("修改失败");
			// }

			if (empty($_POST)) {
                $this->redirect('Admin/Node/add');
                exit;
            }
            //执行修改
            M('node')->create();
            if (M('node')->save() > 0) {
            $this->success('恭喜您,编辑成功!', U('Node/index'));
            } else {
              $this->error('编辑失败....');
                }
		    }

		
	}

 ?>

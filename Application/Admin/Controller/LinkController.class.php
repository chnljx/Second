<?php  
namespace Admin\Controller;
use Think\Controller;

/**
* LinkController 管理友情链接
*
* @author xiao 
* @version 1.0
*/
class LinkController extends AdminController
{
    public function index()
    {
        $data = M('link')->select();
        $this->assign('data',$data);
        $this->assign('title',友情链接);
        $this->assign('part',友情链接列表);
        $this->display();
    }

    /**
    * 链接隐藏
    * @access public        
    * @return void
    */
    public function stop()
    {
        if (IS_AJAX) {
            $data['state'] = 0;
            $link = M('link')->where('id='.I('post.id'))->save($data);
            if ($link == false) {
                $this->ajaxReturn(false);
            } else {
                $this->ajaxReturn(true);

            }
        } else {
            $this->ajaxReturn(false);
        }
        
    }

    /**
    * 链接显示
    * @access public        
    * @return void
    */
    public function start()
    {
        if (IS_AJAX) {
            $data['state'] = 1;
            $link = M('link')->where('id='.I('post.id'))->save($data);
            if ($link == false) {
                $this->ajaxReturn(false); 
            } else {
                $this->ajaxReturn(true);
            }
        } else {
            $this->ajaxReturn(false);
        }
    }

    /**
    * 删除链接
    * @access public        
    * @return void
    */
    public function del()
    {
        if (IS_AJAX) {
            $link = M('link')->where('id='.I('post.id'))->delete();
            if ($link == false) {
                $this->ajaxReturn(false); 
            } else {
                $this->ajaxReturn(true);
            }
        } else {
            $this->ajaxReturn(false);
        }
    }

    /**
    * 添加链接页面
    * @access public        
    * @return void
    */
    public function addView()
    {
        $this->display('Link:add');
    }  


    /**
    * 处理添加链接
    * @access public        
    * @return void
    */
    public function doAdd()
    {   
        $link = D("Link");// 实例化User对象
        if (!$link->create()){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            if(IS_AJAX){    
                $this->ajaxReturn($link->getError());
            }else{
                $this->error($link->getError());
            }
        }else{
            // 验证通过 可以进行其他数据操作
            if(!IS_AJAX){
                $id = $link->add();
                if($id){
                    M('link')->add();
                    $this->success('添加成功');
                }else{
                    $this->error('添加失败');
                }
            }
        }
    }

    /**
    * 修改链接页面
    * @access public        
    * @return void
    */
    public function editView()
    {
        $data = D('Link')->where('id='.I('get.id'))->find();
        $this->assign('data',$data);
        $this->display('Link:edit');
    }  


    /**
    * 处理修改链接
    * @access public        
    * @return void
    */
    public function save()
    {   
        $link = M("Link"); // 实例化User对象
        if (!$link->create()){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            if(IS_AJAX){    
                $this->ajaxReturn($link->getError());
            }else{
                $this->error($link->getError());
            }
        }else{
            // 验证通过 可以进行其他数据操作
            if(!IS_AJAX){
                $data = $link->where('id='.I('post.id'))->save();
                if($data){
                    $this->success('修改成功');
                }else{
                    $this->error('修改失败');
                }
            }
        }
    }
}

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
    protected $barbossoption='';//申请吧主请求分类查询
    protected $baroption='and b.state>1';//创建贴吧请求分类查询
    /**
    * 显示贴吧列表
    * @access public        
    * @return void
    */
    public function index()
    {
        $user = D('bar');
        $user = $user->field('u.name uname, t.name tname, b.id, b.name, b.descr, b.picname, b.ctime, b.state')->table('qm_bar b, qm_type t, qm_user u')->where('b.typeid=t.id and b.uid=u.id and b.state!=2 and b.state!=4')->select();
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
        //得到数据模型
        $bar = D('Bar');
        //过滤数据,数据验证
        if (!$bar->create()) {
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            if(IS_AJAX){   
                $this->ajaxReturn($bar->getError());
            }else{
                $this->error($bar->getError());
            }
               
        } else {
            // 验证通过 可以进行其他数据操作
            if(!IS_AJAX){
                // 执行添加
                if ($bar->add() > 0) {
                    $this->success('添加成功', U('index'));
                } else {
                    $this->error('添加失败');
                }
            }
        }
    }



    /**
    * 修改贴吧信息页面
    * @access public        
    * @return void
    */
    public function edit()
    {
        $this->display();
    }

    /**
    * 执行修改贴吧
    * @access public        
    * @return void
    */
    public function save()
    {
        //得到数据模型
        $bar = D('Bar');
        //过滤数据,数据验证
        if (!$bar->create()) {
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            if(IS_AJAX){   
                $this->ajaxReturn($bar->getError());
            }else{
                $this->error($bar->getError());
            }
               
        } else {
            // 验证通过 可以进行其他数据操作
            if(!IS_AJAX){
                // 执行添加
                if ($bar->add() > 0) {
                    $this->success('添加成功', U('index'));
                } else {
                    $this->error('添加失败');
                }
            }
        }
    }

    /**
    * 用户创建贴吧请求页面
    * @access public        
    * @return void
    */  
  	public function barbeg($option='')
    {
        //创建贴吧请求分类查询
        if (!empty(I('get.option'))) {
            switch(I('get.option')){
                case 'agreebtn':
                    $this->baroption='and b.state=3';
                    break;
                case 'refusebtn':
                    $this->baroption='and b.state=4';
                    break;
                case 'ingbtn':
                    $this->baroption='and b.state=2';
                    break;
                case 'allbtn':
                    $this->baroption='and b.state>1';
                    break;
                default:
                    break;
            }
        }
        $data = D('bar');
        $data = $data->field('u.name uname, t.name tname, b.id, b.name, b.descr, b.picname, b.ctime, b.state')->table('qm_bar b, qm_type t, qm_user u')->where("b.typeid=t.id and b.uid=u.id $this->baroption")->select();
        $this->assign('data',$data);
       	$this->assign('title','贴吧管理');
        $this->assign('part','创建贴吧请求');
        $this->display('Bar:beg-bar');
    }

    /**
    * 用户申请吧主请求页面
    * @access public        
    * @return void
    */  
    // 申请吧主
    public function beg($option='')
    {
        //申请吧主请求分类查询
        if (!empty(I('get.option'))) {
            switch(I('get.option')){
                case 'agreebtn':
                    $this->barbossoption='and bo.state=2';
                    break;
                case 'refusebtn':
                    $this->barbossoption='and bo.state=1';
                    break;
                case 'ingbtn':
                    $this->barbossoption='and bo.state=0';
                    break;
                case 'allbtn':
                    $this->barbossoption='';
                    break;
                default:
                    break;
            }
        }
        $data = D('bar');
        $data = $data->field('bo.id, u.name uname, b.name bname, u.phone, u.email, u.exp, u.picname, bo.ctime, bo.state')->table('qm_bar b, qm_barboss_beg bo, qm_user u')->where("bo.uid=u.id and bo.bid=b.id $this->barbossoption")->select();
        $this->assign('data',$data);
        $this->assign('title','吧主管理');
        $this->assign('part','申请吧主');
        $this->display('Bar:beg-barboss');
    }
}
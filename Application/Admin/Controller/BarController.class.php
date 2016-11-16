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
    protected $baroption='and b.begstate>0';//创建贴吧请求分类查询

    /**
    * 显示贴吧列表
    * @access public        
    * @return void
    */
    public function index()
    {
        $user = D('bar');
        $user = $user->field('u.name uname, t.name tname, b.id, b.name, b.descr, b.picname, b.ctime, b.state')->table('qm_bar b, qm_type t, qm_user u')->where('b.typeid=t.id and b.uid=u.id and b.begstate!=1 and b.begstate!=3')->select();
        $this->assign('title','贴吧管理');
        $this->assign('part','贴吧列表');
        $this->assign('user',$user);
        $this->display();
    }

    /**
    * 贴吧禁用
    * @access public        
    * @return void
    */
    public function stop()
    {
        if (IS_AJAX) {
            $data['state'] = 0;
            $user = M('bar')->where('id='.I('post.id'))->save($data);
            if ($user == false) {
                echo '禁用失败';
                exit;
            }
        }
        
    }

    /**
    * 贴吧开启
    * @access public        
    * @return void
    */
    public function start()
    {
        if (IS_AJAX) {
            $data['state'] = 1;
            $user = M('bar')->where('id='.I('post.id'))->save($data);
            if ($user == false) {
                echo '启用失败';
                exit;
            }
        }
        
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
    public function doAdd()
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
                // 验证通过 可以进行其他数据操作
                if($_FILES['picname']['name'] != ''){
                    $config = array(
                        'maxSize' => 3145728,
                        'rootPath' => './Upload/img/tieba/',
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
                    } else {// 上传成功 获取上传文件信息
                        $path = $info['savepath'].$info['savename'];
                        $image = new \Think\Image();
                        $image->open("./Upload/img/tieba/".$path);
                        // 按照原图的比例生成一个最大为90*90的缩略图并保存为thumb.jpg
                        $path = time().$info['savename'];
                        $image->thumb(100, 100)->save('./Upload/img/tieba-thumb/'.$path);
                    }
                }
                
                if($path != ''){
                    $data['picname'] = $path;
                    $data['name'] = I('post.name');
                    $data['typeid'] = I('post.typeid');
                    $data['descr'] = I('post.descr');
                    $data['state'] = I('post.state');
                    $data['begstate'] = 0;
                    $data['ctime'] = time();
                    // 执行添加
                    if ($bar->add($data) > 0) {
                        $this->success('添加成功', U('index'));
                    } else {
                        $this->error('添加失败');
                    }   
                } else {
                    $this->error('请上传图片');
                }
                
            }
        }
    }


    /**
    * 贴吧查看页面
    * @access public        
    * @return void
    */
    public function show()
    {
        $data = M('bar')->where('id='.I('get.id'))->find();
        $this->assign('list', $data);
        $type = M('type')->Field('name')->where('id='.$data['typeid'])->find();
        $this->assign('type',$type);
        $this->display();
    }


    /**
    * 修改贴吧信息页面
    * @access public        
    * @return void
    */
    public function edit()
    {
        $list = M('bar')->field('u.name uname, t.name tname, b.id, b.name, b.descr, b.picname, b.ctime, b.state')->table('qm_bar b, qm_type t, qm_user u')->where('b.typeid=t.id and b.uid=u.id and b.id='.I('get.id'))->find();
        $this->assign('list',$list);
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
        $bar = D('BarEdit');
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
                // 验证通过 可以进行其他数据操作
                if($_FILES['picname']['name'] != ''){
                    $config = array(
                        'maxSize' => 3145728,
                        'rootPath' => './Upload/img/tieba/',
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
                    } else {// 上传成功 获取上传文件信息
                        $path = $info['savepath'].$info['savename'];
                        $image = new \Think\Image();
                        $image->open("./Upload/img/tieba/".$path);
                        // 按照原图的比例生成一个最大为90*90的缩略图并保存为thumb.jpg
                        $path = time().$info['savename'];
                        $image->thumb(100, 100)->save('./Upload/img/tieba-thumb/'.$path);
                    }
                    
                }
                if($path != ''){
                    $data['picname'] = $path;   
                }
                
                
                $data['descr'] = I('post.descr');
                $data['state'] = I('post.state');
                // 执行添加
                if ($bar->where('id='.I('post.id'))->save($data) > 0) {
                    $this->success('修改成功', U('index'));
                } else {
                    $this->error('修改失败');
                }   
            }
        }
    }

    /**
    * 用户创建贴吧请求页面
    * @access public        
    * @return void
    */  
  	public function barBeg($option='')
    {
        //创建贴吧请求分类查询
        if (!empty(I('get.option'))) {
            switch(I('get.option')){
                case 'agreebtn':
                    $this->baroption='and b.begstate=2';
                    break;
                case 'refusebtn':
                    $this->baroption='and b.begstate=3';
                    break;
                case 'ingbtn':
                    $this->baroption='and b.begstate=1';
                    break;
                case 'allbtn':
                    $this->baroption='and b.begstate>0';
                    break;
                default:
                    break;
            }
        }
        $data = D('bar');
        $data = $data->field('u.name uname, t.name tname, b.id, b.name, b.descr, b.picname, b.ctime, b.begstate, b.uid')->table('qm_bar b, qm_type t, qm_user u')->where("b.typeid=t.id and b.uid=u.id $this->baroption")->select();
        $this->assign('data',$data);
       	$this->assign('title','贴吧管理');
        $this->assign('part','创建贴吧请求');
        $this->display('Bar:beg-bar');
    }


    /**
    * 拒绝用户创建贴吧要求
    * @access public        
    * @return void
    */
    public function refuseBar()
    {
        if (IS_AJAX) {
            $data['begstate'] = 3;
            $user = M('bar')->where('id='.I('post.id'))->save($data);
            if ($user == false) {
                $this->ajaxReturn(false);
                
            } else {
                $this->ajaxReturn(true);
            }
        }
        
    }

    /**
    * 同意用户创建贴吧要求
    * @access public        
    * @return void
    */
    public function agreeBar()
    {
        if (IS_AJAX) {
            
            $data['begstate'] = 2;
            $data['state'] = 1;
            $user = M('bar')->where('id='.I('post.id'))->save($data);
            if ($user == false) {
                $this->ajaxReturn(false);                
               
            } else {
                $this->ajaxReturn(true);
            } 
        }
           
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
        $data = $data->field('bo.id, u.name uname, b.name bname, u.phone, u.email, u.exp, u.sex, bo.ctime, bo.state, bo.uid, bo.bid')->table('qm_bar b, qm_barboss_beg bo, qm_user u')->where("bo.uid=u.id and bo.bid=b.id $this->barbossoption")->select();
        $this->assign('data',$data);
        $this->assign('title','吧主管理');
        $this->assign('part','申请吧主');
        $this->display('Bar:beg-barboss');
    }


    /**
    * 拒绝用户申请吧主要求
    * @access public        
    * @return void
    */
    public function refuseBarBoss()
    {
        if (IS_AJAX) {
            $data['state'] = 1;
            $user = M('barboss_beg')->where('id='.I('post.id'))->save($data);
            if ($user == false) {
                $this->ajaxReturn(false);
            } else {
                $this->ajaxReturn(true);
            }
        }
        
    }

    /**
    * 同意用户申请吧主要求
    * @access public        
    * @return void
    */
    public function agreeBarBoss()
    {
        if (IS_AJAX) {
            $uid = M('bar')->field('uid')->where('id='.I('post.bid'))->find();
            // 判断该贴吧是否已经有吧主  没有 则继续执行
            if ($uid['uid'] == 1) {
                $data['state'] = 2;
                $user = M('barboss_beg')->where('id='.I('post.id'))->save($data);
                // 如果吧主申请表状态没有改变 中断程序
                if ($user == false ) {
                    $this->ajaxReturn(false);
                    exit;
                } else {
                    
                    $bar['uid'] = I('post.uid');
                    $msg = M('bar')->where('id='.I('post.bid'))->save($bar);
                    if ($msg == false) {
                        $bar['uid'] = 1;
                        $msg = M('bar')->where('id='.I('post.bid'))->save($bar);
                        $this->ajaxReturn(false);
                    } else {
                        // 当申请的贴吧已经有吧主时，状态自动成为 拒绝
                        // $same['state']=1;
                        // M('barboss_beg')->where('bid='.I('post.bid').' and id!='.I('post.id'))->save($same);//只能有一个吧主时 除申请成功的吧主外 其他全部申请成为该贴吧的请求状态为已经拒绝 
                        $role['uid'] = I('post.uid');
                        $role['rid'] = 2;
                        $rolenum = M('user_role')->where($role)->find();
                        // 判断用户角色表中是否已有 没有  进行添加
                        if ($rolenum) {
                            $this->ajaxReturn(true);
                        } else {
                            $user_role = M('user_role')->add($role);
                            // 如果用户角色表没有添加成功 则将吧主申请表状态与贴吧的吧主uid恢复
                            if ($user_role == false) {
                                $data['state'] = 0;
                                $bar['uid'] = 1;
                                // $user = M('barboss_beg')->where('bid='.I('post.bid'))->save($data);//只能有一个吧主时 全部申请成为该贴吧的请求状态为请求中 
                                $user = M('barboss_beg')->where('id='.I('post.id'))->save($data);
                                $msg = M('bar')->where('id='.I('post.bid'))->save($bar);
                                $this->ajaxReturn(false);
                            } else {
                                $this->ajaxReturn(true);
                            }
                        }
                        
                    }
                    
                }
            } else {
                $this->ajaxReturn(false);
            }    
        }
           
    }
}
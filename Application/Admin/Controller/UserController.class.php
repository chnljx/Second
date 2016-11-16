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

        $data = M('User')->where('id in'.M('user_role')->field('uid')->where('rid = 3 or rid = 2')->buildSql())->select();
        // V(count($data));exit;
        $this->assign('list', $data);
        $this->assign('num', count($data));
        $this->display();
    }

    public function memberAdd()
    {
        $this->display('User:member-add');
    }

    // 用户添加操作
    public function addAction()
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

    // 禁用帐号操作
    public function memberStop()
    {   
        // echo I('post.id');exit;
        $data['state'] = 0;
        $msg = M('User')->where('id='.I('post.id'))->save($data);
        if($msg === false){
            echo "停用失败";
            exit;
        }
    }

    // 启用帐号操作
    public function memberStart()
    {   
        // echo I('post.id');exit;
        $data['state'] = 1;
        $msg = M('User')->where('id='.I('post.id'))->save($data);
        if($msg === false){
            echo "启用失败";
            exit;
        }
    }

    // 显示用户信息
    public function memberShow()
    {
        $data = M('User')->where('id='.I('get.id'))->find();
        $this->assign('list', $data);
        $this->display('User:member-show');
    }

    // 用户信息修改
    public function memberEdit()
    {
        $data = M('User')->where('id='.I('get.id'))->find();
        $this->assign('list', $data);
        $this->display('User:member-edit');
    }

    // 用户信息修改操作
    public function editAction()
    {
        $User = D("User"); // 实例化User对象
        if (!$User->create()){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            // if(IS_AJAX){    
                // $this->ajaxReturn($User->getError());
            // }else{
                $this->error($User->getError());
            // }
        }else{
            // 验证通过 可以进行其他数据操作
            if($_FILES['picname']['name'] != ''){
                $config = array(
                    'maxSize' => 3145728,
                    'rootPath' => './Upload/img/avatar/',
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
                }else{// 上传成功 获取上传文件信息
                    $path = $info['savepath'].$info['savename'];
                    $image = new \Think\Image();
                    $image->open("./Upload/img/avatar/".$path);
                    // 按照原图的比例生成一个最大为90*90的缩略图并保存为thumb.jpg
                    $path = time().$info['savename'];
                    $image->thumb(90, 90)->save('./Upload/img/avatar-thumb/'.$path);
                }
            }
            
            if($path != ''){
                $map['picname'] = $path;
            }

            if($_POST['birthday'] != ''){
                $map['birthday'] = strtotime($_POST['birthday']);
            }

            $map['sex'] = $_POST['sex'];
            $map['email'] = $_POST['email'];
            $map['phone'] = $_POST['phone'];
            $map['code'] = $_POST['code'];
            $map['descr'] = $_POST['descr'];
            $User->where('id='.$_POST['id'])->save($map); // 根据条件更新记录
            $this->success('修改成功');
        }
    }

    // 吧主列表
    public function list()
    {
        $this->assign('title','用户管理');
        $this->assign('part','吧主列表');
        // $User = M('User')->where('id in'.M('user_role')->field('uid')->where('rid = 2')->buildSql())->select();
        // $User = M('Bar')->where('uid in'.M('user_role')->field('uid')->where('rid = 2')->buildSql())->join('__USER__ ON __BAR__.uid = __USER__.id')->select();
        // $Barname = M('Bar')->field('uid,name')->where('uid in'.M('user_role')->field('uid')->where('rid = 2')->buildSql())->select();
        // $User = M('Bar')->alias('b')->field('qm_user.*,b.name as barname')->where('uid in'.M('user_role')->field('uid')->where('rid = 2')->buildSql())->join('__USER__ ON b.uid = __USER__.id')->select();
        $User = M('User')->field('qm_user.*,qm_bar.name barname')->where('qm_user.id in'.M('user_role')->field('uid')->where('rid = 2')->buildSql())->join('qm_bar ON qm_user.id = qm_bar.uid')->select();
        // foreach($Barname as $v){
        //     $barlist[$v['uid']][] = $v['name'];
        //     //把修改和执行修改 添加和执行添加 拼装到一起
        // }
        // V($User);exit;
        // V($Barname);exit;
        $this->assign('list', $User);
        $this->assign('num', count($User));
        // $this->assign('barlist',  $barlist);
        $this->display('User:barboss-index');
    }

    // 经验值
    public function grade()
    {   
        $data = M('User')->field('id,name,exp,state')->where('id in'.M('user_role')->field('uid')->where('rid = 3 or rid = 2')->buildSql())->select();
        // V($data);exit;
        $this->assign('list', $data);
        $this->assign('num', count($data));
        $this->assign('title','用户管理');
        $this->assign('part','等级列表');
        $this->display('User:member-grade');
    }
}

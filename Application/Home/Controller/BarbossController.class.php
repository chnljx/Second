<?php 
namespace Home\Controller;
use \Think\Controller;

/**
* BarbossController 贴吧资料
*
* @author michael
* @version 1.0
*/
class BarbossController extends HomeController
{
    public function _initialize()
    {
        date_default_timezone_set('PRC');
        if(empty(session('home_user'))){
            $this->error('请先登录', U('Login/index'));
        }
        if (empty(I('get.id'))) {
            $this->display('Public:404');
            exit;
        }

        $boss = M('bar')->where('id='.I('get.id').' and uid='.session('home_user.id'))->find();
        if ($boss == '') {
            $this->error('权限不足', U('Index/index'));
        }
        $bar = M('bar')->field('state, begstate')->where('id='.I('get.id'))->find();
        if($bar['state'] == 0){
            $this->error('该吧已被禁用', U('Index/index'));
        } else if ($bar['begstate'] == 1 || $bar['begstate'] == 3) {
            $this->error('该吧未创建', U('Index/index'));
        }
    }

    public function index()
    {   
        $bar = M('bar')->field('t.name tname, b.id, b.name, b.descr, b.picname, b.ctime, b.state')->table('qm_bar b, qm_type t')->where('b.typeid=t.id and b.id='.I('get.id'))->find();
        $follow = M('Follow')->field('u.name, u.picname, u.exp, u.descr, u.regtime')->table('qm_follow f, qm_user u')->where('f.bid='.I('get.id').' and f.uid=u.id and u.state=1')->order('u.exp desc')->page($_GET['p'],10)->select();

        $count = M('Follow')->table('qm_follow f, qm_user u')->where('f.bid='.I('get.id').' and f.uid=u.id and u.state=1')->count();
        $Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数

        $Page->setConfig('first','首页');
        $Page->setConfig('last','尾页');
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        
        $Page->setConfig('theme','
            <nav>
              <ul class="pagination">
                <li>%FIRST%</li>
                <li>%UP_PAGE%</li>
                <li>%LINK_PAGE%</li>
                <li>%DOWN_PAGE%</li>
                <li>%END%</li>
              </ul>
            </nav>
        ');

        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);
        $this->assign('count',$count);

        $this->assign('bar', $bar);
        $this->assign('follow', $follow);
        $this->display();
    }

    /**
    * 修改贴吧信息页面
    * @access public        
    * @return void
    */
    public function editView()
    {
        $bar = M('bar')->field('t.name tname, b.id, b.name, b.descr, b.picname, b.ctime, b.state')->table('qm_bar b, qm_type t')->where('b.typeid=t.id and b.id='.I('get.id'))->find();
    
        $list = M('bar')->field('u.name uname, t.name tname, b.id, b.name, b.descr, b.picname, b.ctime, b.state')->table('qm_bar b, qm_type t, qm_user u')->where('b.typeid=t.id and b.uid=u.id and b.id='.I('get.id'))->find();
        $this->assign('list',$list);
        $this->assign('bar', $bar);
        $this->display('Barboss/edit');

    }

    /**
    * 执行修改贴吧
    * @access public        
    * @return void
    */
    public function save()
    {
        //得到数据模型
        $bar = D('Barboss');
        $data = $__POST;
        //过滤数据,数据验证
        if (!$bar->create($data)) {
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
                // 执行添加
                if ($bar->where('id='.I('post.id'))->save($data) > 0) {
                    $this->success('修改成功', U('editView').'?id='.I('post.id'));
                } else {
                    $this->error('修改失败');
                }   
            }
        }
    }


    // 密码显示
    // public function pwdview()
    // {
    //     $this->display('User:pwd');
    // }

    // // 密码修改操作
    // public function pwdsave()
    // {
    //     if(md5(I('post.prepasswd')) == session('home_user.passwd')){
    //         if(I('post.passwd') != I('post.confirmpasswd')){
    //             $this->error('两次密码输入不一致');
    //         }

    //         $map['passwd'] = md5(I('post.passwd'));
    //         M('User')->where('id='.session('home_user.id'))->save($map);
    //         session(null);
    //         $this->success("密码修改成功", U('Login/index'));
    //     }else{  
    //         $this->error('原密码错误');
    //     }
    // }

    /*
        头像页
    */ 
    // public function headview()
    // {
    //     $this->display('User:head');
    // }

    // 头像更新操作
    public function headsave()
    {
        if(!empty($_FILES['picname']['tmp_name'])){
                $config = array(
                    'maxSize' => 5242880,
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
                    $path = time().$info['savename'];
                    $image->thumb(140, 150)->save('./Upload/img/avatar-thumb/'.$path);
                    session('home_user.picname', $path);
                    M('User')->where('id='.session('home_user.id'))->save(array('picname'=>$path));
                    $this->success('上传头像成功');
                }
        }else{
            $this->error('请选择图片');
        }
    }

    /*
        通知列表
    */ 
    // public function msgview()
    // {   
        /*
            $comment = M('Comment')->alias('c')->field('c.uid,c.content,c.ctime,p.id as pid,p.title,b.id as bid,b.name as bname,u.name as uname')->where("c.uid=".session('home_user.id'))->join('__POST__ as p ON c.postid = p.id')->join('__BAR__ as b ON p.bid = b.id')->join('__USER__ as u ON c.uid = u.id')->select();
            $this->assign('comment', $comment);
        */


    //     $Comment = M('Comment'); // 实例化Comment对象
    //     // 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
    //     $list = $Comment->alias('c')->field('c.uid,c.content,c.ctime,p.id as pid,p.title,b.id as bid,b.name as bname,u.name as uname')->where("c.uid=".session('home_user.id'))->join('__POST__ as p ON c.postid = p.id')->join('__BAR__ as b ON p.bid = b.id')->join('__USER__ as u ON c.uid = u.id')->order('c.ctime desc')->page($_GET['p'].',6')->select();
    //     $this->assign('list',$list);// 赋值数据集
    //     $count = $Comment->alias('c')->field('c.uid,c.content,c.ctime,p.id as pid,p.title,b.id as bid,b.name as bname,u.name as uname')->where("c.uid=".session('home_user.id'))->join('__POST__ as p ON c.postid = p.id')->join('__BAR__ as b ON p.bid = b.id')->join('__USER__ as u ON c.uid = u.id')->count();// 查询满足要求的总记录数
    //     $Page = new \Think\Page($count,6);// 实例化分页类 传入总记录数和每页显示的记录数

    //     $Page->setConfig('first','首页');
    //     $Page->setConfig('last','尾页');
    //     $Page->setConfig('prev','上一页');
    //     $Page->setConfig('next','下一页');
        
    //     $Page->setConfig('theme','
    //         <nav>
    //           <ul class="pagination">
    //             <li>%FIRST%</li>
    //             <li>%UP_PAGE%</li>
    //             <li>%LINK_PAGE%</li>
    //             <li>%DOWN_PAGE%</li>
    //             <li>%END%</li>
    //           </ul>
    //         </nav>
    //     ');

    //     $show = $Page->show();// 分页显示输出
    //     $this->assign('page',$show);// 赋值分页输出


    //     $this->display('User:msg');
    // }

    // public function postview()
    // {
    //     $post = M('Post');
    //     $list = $post->alias('p')->field('b.id as bid,b.name as bname,p.id as pid,p.title as ptitle,p.ctime as pctime')->where("p.uid=".session('home_user.id'))->join('__BAR__ as b ON p.bid = b.id')->order('p.ctime desc')->page($_GET['p'].',10')->select();
    //     $this->assign('list',$list);
    //     $count = $post->alias('p')->field('b.id as bid,b.name as bname,p.id as pid,p.title as ptitle,p.ctime as pctime')->where("p.uid=".session('home_user.id'))->join('__BAR__ as b ON p.bid = b.id')->count();// 查询满足要求的总记录数
    //     $Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数

    //     $Page->setConfig('first','首页');
    //     $Page->setConfig('last','尾页');
    //     $Page->setConfig('prev','上一页');
    //     $Page->setConfig('next','下一页');
        
    //     $Page->setConfig('theme','
    //         <nav>
    //           <ul class="pagination">
    //             <li>%FIRST%</li>
    //             <li>%UP_PAGE%</li>
    //             <li>%LINK_PAGE%</li>
    //             <li>%DOWN_PAGE%</li>
    //             <li>%END%</li>
    //           </ul>
    //         </nav>
    //     ');

    //     $show = $Page->show();// 分页显示输出
    //     $this->assign('page',$show);// 赋值分页输出

    //     $this->display('User:post');
    // }

    // public function collectionview()
    // {
    //     $collection = M('Collection');

    //     $list = $collection->alias('co')->field('p.title as ptitle,p.id as pid,b.name as bname,b.id as bid,u.id as uid,u.name as uname')->where("co.selfid=".session('home_user.id'))->join('__POST__ as p ON p.id = co.postid')->join('__BAR__ as b ON p.bid = b.id')->join('__USER__ as u on p.uid = u.id')->page($_GET['p'].',10')->select();
    //     $this->assign('list',$list);
    //     $count = $collection->alias('co')->field('p.title as ptitle,p.id as pid,b.name as bname,b.id as bid,u.id as uid,u.name as uname')->where("co.selfid=".session('home_user.id'))->join('__POST__ as p ON p.id = co.postid')->join('__BAR__ as b ON p.bid = b.id')->join('__USER__ as u on p.uid = u.id')->count();// 查询满足要求的总记录数
    //     $Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数

    //     $Page->setConfig('first','首页');
    //     $Page->setConfig('last','尾页');
    //     $Page->setConfig('prev','上一页');
    //     $Page->setConfig('next','下一页');
        
    //     $Page->setConfig('theme','
    //         <nav>
    //           <ul class="pagination">
    //             <li>%FIRST%</li>
    //             <li>%UP_PAGE%</li>
    //             <li>%LINK_PAGE%</li>
    //             <li>%DOWN_PAGE%</li>
    //             <li>%END%</li>
    //           </ul>
    //         </nav>
    //     ');

    //     $show = $Page->show();// 分页显示输出
    //     $this->assign('page',$show);// 赋值分页输出
        
    //     $this->display('User:collection');
    // }
    
}
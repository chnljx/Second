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
    
}
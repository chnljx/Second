<?php
namespace Home\Controller;
use Think\Controller;

/**
*BarController 贴吧控制器
* @author xiao
* @version 1.0
*/
class BarController extends HomeController 
{

    // public function _initialize()
    // {
    //     if(empty(session('home_user'))){
    //         $this->error('请先登录', U('Login/index'));
    //     }
    // }

    public function index()
    {
        session_start();
        if (empty(I('get.id'))) {
            $this->display('Public:404');
            exit;
        }
        if (!empty($_SESSION['home_user'])) {
            $user = M('follow')->where('bid='.I('get.id').' and uid='.$_SESSION['home_user']['id'])->find();
        }
        // 图片轮播
        $data = M('picture')->where('bid='.I('get.id'))->limit(12)->select();
        // 贴吧名
        $bar = M('bar')->field('b.name, b.picname, b.id, u.name uname, b.descr, t.name tname')->table('qm_bar b,qm_user u, qm_type t')->where('b.id='.I('get.id').' and b.uid=u.id and t.id=b.typeid')->find();
        // 关注人数
        $follow = M('follow')->where('bid='.I('get.id'))->count();
        // 帖子
        $list = M('post')->field('u.name uname,u.picname upic, p.title, p.descr, p.state, p.ctime, p.id')->table('qm_user u,qm_post p')->where('p.bid='.I('get.id').' and p.state=1 and p.uid=u.id')->order('p.ctime desc')->page($_GET['p'],5)->select();
        // 分页
        $count = M('post')->table('qm_user u,qm_post p')->where('p.bid='.I('get.id').' and p.state=1 and p.uid=u.id')->count();

        $Page = new \Think\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);

        // 贴吧名人
        $supfans = M('follow')->field('u.name, u.exp, u.picname')->table('qm_user u, qm_follow f')->where('f.bid='.I('get.id').' and f.uid=u.id')->order('u.exp desc')->limit(3)->select();

        foreach ($list as $k=>$v) {
            // 收藏
            $num = M('collection')->where('postid='.$v['id'])->select();
            // 回复
            $nums = M('comment')->where('postid='.$v['id'])->select();
            // $pics = M('post_photo')->field('picname')->where('postid='.$v['id'])->limit(1)->select();
            $list[$k]['count']=count($num);
            $list[$k]['comcount']=count($nums);
            // $list[$k]['pics']=$pics[0]['picname'];
            // $list[$k]['picnum']=count($pics);

        }

        $this->assign('datas',$data);
        $this->assign('user',$user);
        $this->assign('list',$list);
        $this->assign('num',$count);
        $this->assign('bar',$bar);
        $this->assign('follow',$follow);
        $this->assign('supfans',$supfans);


        // 天气
        $ch = curl_init();
        $c= empty($_POST['city'])?'shanghai':$_POST['city'];

          // var_dump($c);exit;
        $location = $c;
        $url = 'http://apis.baidu.com/thinkpage/weather_api/suggestion?location='.$location.'&language=zh-Hans&unit=c&start=0&days=3';
        $header = array(
            'apikey: b848d17197e4ead6adc43f2af62b4ac8',
        );
        // 添加apikey到header
        curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 执行HTTP请求
        curl_setopt($ch , CURLOPT_URL , $url);
        $res = curl_exec($ch);

        $res = json_decode($res);
        // dump($res);
        // exit;
        $data = $res->results;
        
        $city = $data[0]->location->name;
        $daily = $data[0]->daily;
        // dump($daily);
        $update = $data[0]->last_update;
        
        // $linklist=M('link')->select();
        // $this->assign('linklist',$linklist);
        $this->assign('city',$city);
        $this->assign('update',$update);
        $this->assign('daily',$daily);
        $this->display();
    }
    

    // 贴吧创建申请
    public function apply()
    {
        if(empty(session('home_user'))){
            $this->error('请先登录', U('Login/index'));
        }

        $type = D('Type');
        $list = $type->getAdminCate();
        $this->assign('list',$list);
        $this->display();
    } 

    public function doapply()
    {
        if(!IS_POST){
            $this->error('数据传入非法');
        }

        $Bar = D("Bar"); // 实例化User对象
        if (!$Bar->create()){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            $this->error($Bar->getError());
        }else{
            // 验证通过 可以进行其他数据操作

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


            $Bar->name = rtrim(I('post.name'), '吧').'吧';
            $Bar->uid = session('home_user.id');
            $Bar->picname = $path;
            // $Bar->typeid = I('post.typeid');
            // $Bar->descr = I('post.descr');
            if($Bar->add()){
                $this->success('贴吧申请成功');
            }else{
                $this->error('贴吧申请失败');
            }
        }


    public function dosearch()
    {
       if(!IS_AJAX){
        $name = I('post.name');
        // var_dump($name);
        $data=M('bar')->where("name='$name'")->find();
        if($data){
            $id=$data['id'];
            $this->redirect("bar/index?id='$id'");
        }else{
            $this->error('没有该吧');
        }
       } 
    }

    // 申请吧主
    public function boss() 
    {
        if(empty(session('home_user')))
        {
            $this->error('请先登录', U('Login/index'));
            exit;
        }


        $arr = M('bar')->field('id, name, uid')->where('id='.I('get.bid'))->find();
        if ($arr['uid'] != 1) {
             $this->error('该吧已有吧主,申请失败',U('Index/index'));
             exit;
        }  

        $this->assign('arr',$arr);
        $this->display();  
   

    }
}

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

    public function _initialize()
    {
        if (empty(I('get.id'))) {
            $this->display('Public:404');
            exit;
        }
        
        $bar = M('bar')->field('state, begstate')->where('id='.I('get.id'))->find();
        if ($bar['begstate'] == 1 || $bar['begstate'] == 3) {
            $this->error('该吧未创建', U('Index/index'));
        } elseif ($bar['state'] == 0){
            $this->error('该吧已被禁用', U('Index/index'));
        }  
    }

    public function index()
    {
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
        $bar = M('bar')->field('b.name, b.picname, b.id, b.uid, u.name uname, u.descr udescr, u.picname upic, b.descr, t.name tname')->table('qm_bar b,qm_user u, qm_type t')->where('b.id='.I('get.id').' and b.uid=u.id and t.id=b.typeid')->find();

        if ($bar.uid != 1) {
            $counts['follow'] = M('follow')->where('uid='.$bar['uid'])->count();
            $counts['post'] = M('post')->where('uid='.$bar['uid'])->count();

        }
        // 关注人数
        $follow = M('follow')->where('bid='.I('get.id'))->count();
        // 帖子

        $list = M('post')->field('u.name uname,u.picname upic, p.title, p.descr, p.state, p.ctime, p.id')->table('qm_user u,qm_post p')->where('p.bid='.I('get.id').' and p.state=1 and p.uid=u.id and p.state=1')->order('p.ctime desc')->page($_GET['p'],5)->select();

        // 分页
        $count = M('post')->table('qm_user u,qm_post p')->where('p.bid='.I('get.id').' and p.state=1 and p.uid=u.id')->count();

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
        $this->assign('count',$counts);



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

    // 申请吧主
    public function boss() 
    {
        if(empty(session('home_user')))
        {
            $this->error('请先登录', U('Login/index'));
            exit;
        }

        $arr = M('bar')->field('id, name, uid')->where('id='.I('get.id'))->find();

        if ($arr['uid'] != 1) {
             $this->error('该吧已有吧主,申请失败',U('Index/index'));
             exit;
        }  

        $this->assign('arr',$arr);
        $this->display();
    }

    
    public function send()
    {
        $data['bid'] = I('post.bid');
        $data['uid'] = session('home_user.id');
        $data['ctime'] = time();
        if (M('barboss_beg')->create($data)) {
            $send = M('barboss_beg')->add();
            if ($send>0) {
                $this->success('已发送申请，请等待管理员处理',U('Bar/index',array('id'=>$data['bid'])));
            }  else {
                $this->error('发送申请失败');
            }
        }
        
    }

    public function barphoto()
    {
       
        if (empty(I('get.id'))) {
            $this->display('Public:404');
            exit;
        }
        if (!empty($_SESSION['home_user'])) {
            $user = M('follow')->where('bid='.I('get.id').' and uid='.$_SESSION['home_user']['id'])->find();
        }
        // 贴吧名
        $bar = M('bar')->field('b.name, b.picname, b.id, b.uid, u.name uname, u.descr udescr, u.picname upic, b.descr, t.name tname')->table('qm_bar b,qm_user u, qm_type t')->where('b.id='.I('get.id').' and b.uid=u.id and t.id=b.typeid')->find();

        if ($bar.uid != 1) {
            $counts['follow'] = M('follow')->where('uid='.$bar['uid'])->count();
            $counts['post'] = M('post')->where('uid='.$bar['uid'])->count();

        }
        // 关注人数
        $follow = M('follow')->where('bid='.I('get.id'))->count();

        // 图片
        $list=M('picture')->where('bid='.I('get.id'))->page($_GET['p'],12)->select();

        // 分页
        $count = M('picture')->where('bid='.I('get.id'))->count();

        $Page = new \Think\Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数

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
        $this->assign('count',$counts);
        $this->display();
    }

}

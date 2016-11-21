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
    public function index()
    {
        // 图片轮播
        $data = M('picture')->where('bid=46')->limit(12)->select();
        // 贴吧名
        $bar = M('bar')->field('b.name, b.picname, b.id, u.name uname, b.descr, t.name tname')->table('qm_bar b,qm_user u, qm_type t')->where('b.id=46 and b.uid=u.id and t.id=b.typeid')->find();
        // 关注人数
        $follow = M('follow')->where('bid=46')->select();
        // 帖子
        $list = M('post')->field('u.name uname, p.title, p.descr, p.state, p.ctime, p.id')->table('qm_user u,qm_post p')->where('p.bid=46 and p.state=1 and p.uid=u.id')->order('p.ctime desc')->page($_GET['p'],5)->select();
        // 分页
        $count = M('post')->table('qm_user u,qm_post p')->where('p.bid=46 and p.state=1 and p.uid=u.id')->count();

        $Page = new \Think\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);

        // 贴吧名人
        $supfans = M('follow')->field('u.name, u.exp, u.picname')->table('qm_user u, qm_follow f')->where('f.bid=46 and f.uid=u.id')->order('u.exp desc')->select();


        foreach ($list as $k=>$v) {
            // 收藏
            $num = M('collection')->where('postid='.$v['id'])->select();
            // 回复
            $nums = M('comment')->where('postid='.$v['id'])->select();
            $pics = M('post_photo')->field('picname')->where('postid='.$v['id'])->limit(1)->select();
            $list[$k]['count']=count($num);
            $list[$k]['comcount']=count($nums);
            $list[$k]['pics']=$pics[0]['picname'];
            $list[$k]['picnum']=count($pics);

        }

        $this->assign('datas',$data);
        $this->assign('list',$list);
        $this->assign('num',$count);
        $this->assign('bar',$bar);
        $this->assign('follow',count($follow));
        $this->assign('supfans',$supfans);
        $this->display();
    }
    
}

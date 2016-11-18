<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends HomeController {
    public function index()
    {

        $photo=M('carousel')->select();

        if(!empty($_SESSION['home_user'])){

            $id=$_SESSION['home_user']['id'];
            $data=M('')->table('qm_bar b,qm_follow f')->where("b.id=f.bid and f.uid='$id'")->select();
           // var_dump($data);
        }

        $this->assign('title','首页');
        $this->assign('photo',$photo);
        $this->assign('list',$data);
        $this->display();
    }


    // 友情链接
    public function footer()
    {
        $n = -1;
        $num = 0;
        $data = M('link')->where('state=1')->limit(16)->select();
        // var_dump($data);
        foreach ($data as $v) {
            
            if ($num%4!=0) {
                $arr[$n][] = $v;
            } else {
                $n++;
                $arr[$n][] = $v;
            }
            $num++;
        }
        $this->assign('data',$arr);
        $this->display();
    }
}

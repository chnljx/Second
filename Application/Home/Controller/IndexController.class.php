<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends HomeController {
    public function index()
    {

        $photo=M('carousel')->where('state=1')->select();
        // var_dump($photo);

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
}

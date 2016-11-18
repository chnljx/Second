<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends HomeController {
    public function index(){
        $this->assign('title','首页');
        if(!empty($_SESSION['home_user'])){

            $mes="我在贴吧";
            $mes1="注册时间:";
            $mes2="爱逛的吧:";

            $id=$_SESSION['home_user']['id'];
            // var_dump($id);
            $data=M('')->table('qm_bar b,qm_follow f')->where("b.id=f.bid and f.uid='$id'")->select();
            if(empty($data)){
                $emp="你还没有常逛的吧，快去找找吧>>>>";
            }
        }
        
        $this->assign('mes',$mes);
        $this->assign('mes1',$mes1);
        $this->assign('mes2',$mes2);
        $this->assign('emp',$emp);

        $this->assign('list',$data);
        $this->display();
    }
}

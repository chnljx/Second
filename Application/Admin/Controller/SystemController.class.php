<?php  
namespace Admin\Controller;
use Think\Controller;

class SystemController extends AdminController
{
    public function index()
    {
        $types = M('type')->where('pid=0')->select();
        foreach ($types as $v) {
            
        }
        // $types = M('bar')->field('t.name,count(b.id) count')->table('qm_type t,qm_bar b')->where('t.id=b.typeid and t.pid=0')->group('b.typeid')->select();

        // var_dump($types);
        // //以性别分组 统计人数
        // $data = $user->field('sex,count(*)')->group('sex')->select();
        // //以性别分组,过滤出人数小于10人的
        // $data = $user->field('sex,count(*)')->group('sex')->having('count(*)<10')->select();
        // $this->assign('types',$types);

        $this->display();
    }

    
}
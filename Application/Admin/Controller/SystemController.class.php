<?php  
namespace Admin\Controller;
use Think\Controller;

class SystemController extends AdminController
{
    // 统计总分类下的贴吧数
    public function index()
    {

        $arr = [];
        $types = M('type')->where('pid=0')->select();
        foreach ($types as $v) {
            $data = M('bar')->field('count(b.id) count,t.path,t.name,t.id')->table('qm_type t, qm_bar b')->where('t.pid=0 and b.typeid=t.id and t.id='.$v['id'])->select();
            $arr[$v['id']]['count'] = $data[0]['count'];
            $arr[$v['id']]['name'] = $data[0]['name'];
            $arr[$v['id']]['rgb'] = rand(5,230).','.rand(5,230).','.rand(5,230);

            // 拼接路径
            $where = $data[0]['path'].$data[0]['id'];
            $array = M('type')->where("path like '%{$where}%'")->select();
            foreach ($array as $vs) {

                // 查询子分类下的贴吧数
                $num = M('bar')->field('count(id) count')->where('typeid='.$vs['id'])->select();
                // 加上子分类下的贴吧数
                $arr[$v['id']]['count'] += $num[0]['count'];
            }      
        }
        
        $this->assign('data',$arr);
        

        $this->display();
    }

    
}
<?php  
namespace Admin\Controller;
use Think\Controller;

class SystemController extends AdminController
{
    private $where = array('ctime'=>'');  // 角色名称

    // 统计总分类下的贴吧数  饼状图
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

    //系统统计表 
    public function desktop()
    {
        var_dump($_POST);
        $value = empty(I('post.v'))?0:I('post.v');
        var_dump($value);
        switch ($value) {
            // 全部
            case 0:
                $where = "";
                break;
            // 今日
            case 1:
                $where = "'ctime<'.strtotime('tomorrow').' and ctime>'.strtotime('today')";
                break;
            // 昨日
            case 2:
                $where = "'ctime<'.strtotime('today').' and ctime>'.strtotime('yesterday')";
                break;
            // 本周
            case 3:
                
                break;
            // 本月
            case 4:
                
                break;
            default:
                # code...
                break;
        }
        // 统计贴吧分类
        $type = M('type')->field('count(*) num')->select();
        // 统计链接
        $link = M('link')->field('count(*) num')->where($where)->select();
        // 统计贴吧
        $bar = M('bar')->field('count(*) num')->where($where)->select();
        // 统计帖子
        $post = M('post')->field('count(*) num')->where($where)->select();
        // 统计用户
        $user = M('user_role')->field('count(ur.uid) num')->table('qm_user_role ur,qm_role r')->where('r.id=ur.rid and (r.name="普通用户" or r.name="吧主")'.$search)->select();

        if ($where == '') {
            $search = '';
        } else {
            $search = ' and '.$where;
        }
        // 统计管理员
        $huser =M('user_role')->field('count(ur.uid) num')->table('qm_user_role ur,qm_role r')->where('r.id=ur.rid and r.name="管理员"'.$search)->select();

        // 总数
        $this->assign('type', $type[0]);
        $this->assign('link', $link[0]);
        $this->assign('bar', $bar[0]);
        $this->assign('post', $post[0]);
        $this->assign('user', $user[0]);
        $this->assign('huser', $huser[0]);

        // 本周
        // $linktoday = M('link')->field('count(*) num')->where('ctime<'.strtotime('tomorrow').' and ctime>'.strtotime('today'))->select();
        // $usertoday = $user->where($this->where)->select();
        // var_dump($linktoday);
        $this->display();
    }
    
}
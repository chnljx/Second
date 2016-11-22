<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends HomeController {
    public function index()
    {
        // 贴吧分类
        $type_bar=M('type')->where('pid=0')->select();
        $ptype = $type_bar;
        /*dump($ptype);
        die;*/

        foreach ($type_bar as $k => $v) {
            $b_id=$v['id'];
            $types=M('type')->where("pid='$b_id'")->select();
            foreach ($types as $key => $value) {
                $ptype[$k]['child'][] = $value;
                //dump($ptype);
            }
        }
        // dump($ptype);
        // die;


        // 热血动漫
        $t_name="热血动漫";
        $t_id=M('type')->where("name='$t_name'")->field('id')->find();
        $tid=$t_id['id'];
        $bar=M('bar')->where('state=1')->order('id desc')->select();
        $dmbar=M('bar')->where("state=1 and typeid='$tid'")->limit(2)->select();
        $zxbar=M('bar')->where("state=1 and typeid='$tid'")->order('id desc')->find();

        // 内地综艺
        $typename="内地综艺";
        $typename_id=M('type')->field('id')->where("name='$typename'")->find();
        $bar_type_id=$typename_id['id'];
        $bar_mes=M('bar')->where("state=1 and typeid='$bar_type_id'")->limit('2')->select();
        // var_dump($bar_mes);

        // 豪友俱乐部
        $user_exp=M('user')->where("state=1 and name!='admin'")->order('exp desc')->limit('5')->select();
        // var_dump($user_exp);

       // 摄影吧图片
        $name="摄影吧";
        $bar_id=M('bar')->field('id')->where("state=1 and name='$name'")->find();
        $barid=$bar_id['id'];
        $picture=M('picture')->field('picname')->where("bid='$barid'")->select();
        $a=0;
        foreach ($picture as $k => $v) {
            // var_dump($v);
            if($k%2==1){
                $arr[$a][]=$v['picname'];
                // echo '<hr>';
                // var_dump($arr);
            }else{
                $a++;
                $arr[$a][]=$v['picname'];
            }
        }        

        // 轮播图
        $photo=M('carousel')->where('state=1')->order('id desc')->limit('5')->select();
        // var_dump($photo);

        if(!empty($_SESSION['home_user'])){

            $id=$_SESSION['home_user']['id'];
            $data=M('')->table('qm_bar b,qm_follow f')->where("b.id=f.bid and f.uid='$id'")->select();
           // var_dump($data);
        }


        // 新闻接口
        $curl=curl_init();
        // var_dump($curl);

        // 设置APIKEY url 形式
        $apikey="85b64c40553fa3027b064ca0d7e53b7e";

        // URL设置
        curl_setopt($curl, CURLOPT_URL, 'http://api.tianapi.com/keji/?key='.$apikey.'&num=5');
        // 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // curl 执行
        $datas=curl_exec($curl);
        // var_dump($data);

        // 关闭curl
        curl_close($curl);
        // 处理JSON数据
        $jsonObj=json_decode($datas);
        // 提取文章列表
        $newslist=$jsonObj->newslist;
        // var_dump($newslist);
        foreach ($newslist as $key => $value) {
            $array=[];
            foreach ($value as $k => $v) {
                $array[$k]=$v;
            }
            // dump($arr);
            $c[]=$array;
            // dump($c);
        }

        $this->assign('c',$c);

        $this->assign('title','首页');
        $this->assign('bar',$bar);
        $this->assign('dmbar',$dmbar);
        $this->assign('zxbar',$zxbar);

        $this->assign('ptype',$ptype);

        $this->assign('user_exp',$user_exp);

        $this->assign('bar_mes',$bar_mes);

        $this->assign('arr',$arr);

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


}

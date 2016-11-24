<?php
namespace Home\Controller;
use Think\Controller;

/**
*BarController 分类控制器
* @author yn
* @version 1.0
*/

class TypeController extends HomeController
{
	public function index()
	{
		$id=I('get.id');
		$name=M('type')->where("id='$id'")->field('name')->find();
		$data=M('bar')->where("typeid='$id'")->select();
		$datas=$data;
		foreach ($data as $k => $v) {
			$d_id=$v['id'];
			$follow=M('follow')->where("bid='$d_id'")->count('id');
			$post_num=M('post')->where("bid='$d_id'")->count('id');
			$post=M('post')->where("bid='$d_id'")->order('id desc')->find();

			$datas[$k]['num1']=$follow;
			$datas[$k]['num2']=$post_num;
			$datas[$k]['postid']=$post['id'];
			$datas[$k]['title']=$post['title'];
		}
		// var_dump($datas);die;


		// 贴吧分类
        $type_bar=M('type')->where('pid=0')->select();
        $ptype = $type_bar;
		 foreach ($type_bar as $k => $v) {
            $b_id=$v['id'];
            $types=M('type')->where("pid='$b_id'")->select();
            foreach ($types as $key => $value) {
                $ptype[$k]['child'][] = $value;
                
            }
        }

		$this->assign('name',$name);
		$this->assign('ptype',$ptype);
		$this->assign('list',$datas);
		$this->display();
	}

    public function dosearch()
    {

       if(IS_AJAX){
        $name = I('post.name');
        // var_dump($name);
        $data=M('bar')->where("name='$name'")->find();
        if($data){
            $id=$data['id'];
            $this->ajaxReturn($id);
        }else{
            $this->ajaxReturn(false);
        }
       } 
    }

    public function robet()
    {
    	$this->display();
    }


   //机器人接口
        public function jiqi()
        {
           
            if($_POST){
                $ch = curl_init();
                $info = $_POST['info'];
                $info = urlencode($info);
                $url = 'http://apis.baidu.com/turing/turing/turing?key=879a6cb3afb84dbf4fc84a1df2ab7319&info='.$info;
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
                $data = $res->text_array;
                $news = $data[0]->text;
                 $this->assign('news',$news);
             }
             $this->display('Type/robet');
        }

    public function weixi()
    {
        $this->display();
    }
//微信精选接口
public function wx()
{
    $ch = curl_init();
    $infos = '精选';
    $infos = urlencode($infos);
    $url = 'http://apis.baidu.com/txapi/weixin/wxhot?num=10&rand=1&word='.$infos.'&page=1&src=%E4%BA%BA%E6%B0%91%E6%97%A5%E6%8A%A5';
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
    $infos = $res->newslist;
    // var_dump($info);
    $this->assign('infos',$infos);
   
    $this->display('Type/wx');
}


}
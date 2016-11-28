<?php
namespace Home\Controller;
use Think\Controller;

/**
*PostController 帖子控制器
* @author xiao
* @version 1.0
*/
class PostController extends HomeController
{
	public function index()
	{
        if (empty(I('get.id'))) {
            $this->display('Public:404');
            exit;
        }

        $post = M('post')->field('state,bid')->where('id='.I('get.id'))->find();
        if($post['state'] == 0){
            $this->error('该帖已被删除',U('Bar/index',array('id'=>$post['bid'])));
            exit;
        }

        $bar = M('bar')->field('state')->where('id='.$post['bid'])->find();
        if($bar['state'] == 0){
            $this->error('该吧已被禁用', U('Index/index'));
            exit;
        }
        // 楼主
        $arr = M('post')->field('b.name bname, b.picname bpic, u.name uname, u.picname upic, u.exp, p.uid ,p.bid, p.title, p.descr, p.ctime, p.id')->table('qm_user u, qm_bar b, qm_post p')->where('p.id='.I('get.id').' and u.id=p.uid and b.id=p.bid')->find();
        if (!empty($_SESSION['home_user'])) {
            $user = M('follow')->where('bid='.$arr['bid'].' and uid='.$_SESSION['home_user']['id'])->find();
            $coll = M('collection')->where('postid='.I('get.id').' and selfid='.$_SESSION['home_user']['id'])->find();
            
        }
        // 分页
        $arr['p']=$_GET['p'];
        // 关注人数
        $follow = M('follow')->where('bid='.$arr['bid'])->count();
        // 帖子数
        $post = M('post')->where('bid='.$arr['bid'])->count();
        // 评论
        $comment = M('comment')->field('u.name name, u.picname upic, u.exp, c.uid, c.ctime, c.content,c.id')->table('qm_user u, qm_comment c')->where('c.postid='.I('get.id').' and c.uid=u.id and c.state=1')->page($_GET['p'],5)->select();

        // 分页
        $count = M('comment')->table('qm_user u, qm_comment c')->where('c.postid='.I('get.id').' and c.uid=u.id and c.state=1')->count();

         // 贴吧名人
        $supfans = M('follow')->field('u.name, u.exp, u.picname')->table('qm_user u, qm_follow f')->where('f.bid='.$arr['bid'].' and f.uid=u.id')->order('u.exp desc')->limit(3)->select();

        // 回复
        foreach ($comment as $k => $v) {
            $comment[$k]['r'] = M('reply')->field('u.name, u.picname, r.content, u.id, r.id rid')->table('qm_user u,qm_reply r')->where('r.cmtid='.$v['id'].' and r.uid=u.id and r.state=1')->select();
            foreach ($comment[$k]['r'] as $ks=>$vs) {
                $comment[$k]['r'][$ks]['t']= M('reply')->field('u.name')->table('qm_user u,qm_reply r')->where('r.id='.$vs['rid'].' and r.to_uid=u.id and r.uid='.$vs['id'])->find();
            }
        }
        // dump($comment);
        $Page = new \Think\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数
        
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);
        $this->assign('user',$user);
        $this->assign('coll',$coll);
        $this->assign('supfans',$supfans);
        $this->assign('comment',$comment);
        $this->assign('follow',$follow);
        $this->assign('post',$post);
        $this->assign('arr',$arr);

        $this->display();

	}

    // 添加帖子
    public function doAdd()
    {
        if (empty($_SESSION['home_user'])) {
            $this->error('登录后再发帖，请先登录！！',U('Login/index'));
            exit;
        }

        $bar = M('bar')->field('state')->where('id='.I('post.bid'))->find();
        if($bar['state'] == 0){
            $this->error('该吧已被禁用', U('Index/index'));
            exit;
        }


        $data = $_POST;
        
        $post = D("Post"); // 实例化User对象
        if (!$post->create($data)){ 
            $this->error($post->getError());
        }else{
            // 执行添加
            if ($post->add() > 0) {
                $exps = M('User')->where('id='.$data['uid'])->find();
                $exp['exp']=100+$exps['exp'];
                M('User')->where('id='.$data['uid'])->save($exp);
                $this->success('添加成功', U('Bar/index',array('id'=>$data['bid'])));
            } else {
                $this->error('添加失败');
            }   
        }
    }

    //百度分享
    public function baidu()
    {
        $this->display('Post/index');
    }

    // 雷人笑话 接口
    public function joke()
    {
        
        $curl = curl_init();
        // var_dump($curl);//得到资源

        // 设置APIKEY url形式
        $apikey = "c7d6375287125e39a083e2c6e9372840";
        //URL 设置
        curl_setopt($curl, CURLOPT_URL, 'http://api.tianapi.com/txapi/joke/?key='.$apikey);
        //将 curl_exec() 获取的信息以 文件流的形式返回,而不是直接输出
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //curl执行
        $data = curl_exec($curl);
        // var_dump($data);//得到的是一个json字串

        //关闭curl
        curl_close($curl);

        //处理JSON数据
        $jsonObj = json_decode($data);
        //提取文章列表
        $newslist = $jsonObj->newslist;
        // var_dump($newslist[0]);
        $list = $newslist[0];
        $arr['title'] = $list->title;
        $arr['type'] = $list->type;
        $arr['content'] = $list->content;
        $this->assign('list',$arr);
        $this->display();  
    }

    // 脑筋急转弯 接口
    public function word()
    {
        
        $curl = curl_init();
        // var_dump($curl);//得到资源

        // 设置APIKEY url形式
        $apikey = "c7d6375287125e39a083e2c6e9372840";
        //URL 设置
        curl_setopt($curl, CURLOPT_URL, 'http://api.tianapi.com/huabian/?key='.$apikey.'&num=10');
        //将 curl_exec() 获取的信息以 文件流的形式返回,而不是直接输出
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //curl执行
        $data = curl_exec($curl);
        // var_dump($data);//得到的是一个json字串

        //关闭curl
        curl_close($curl);

        //处理JSON数据
        $jsonObj = json_decode($data);
        //提取文章列表
        $newslist = $jsonObj->newslist;
        
        foreach ($newslist as $k => $v) {
            $arr[$k]['ctime'] =$v->ctime;
            $arr[$k]['title'] =$v->title;
            $arr[$k]['url'] =$v->url;
            $arr[$k]['description'] =$v->description;
            $arr[$k]['picUrl'] =$v->picUrl;

        }
        
        $this->assign('list',$arr);
        $this->display();  
    }
}

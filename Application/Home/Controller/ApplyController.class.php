<?php 
namespace Home\Controller;
use \Think\Controller;

/**
* BarbossController 贴吧资料
*
* @author michael
* @version 1.0
*/
class ApplyController extends HomeController
{
    // 贴吧创建申请
    public function index()
    {
        if(empty(session('home_user'))){
            $this->error('请先登录', U('Login/index'));
        }

        $type = D('Type');
        $list = $type->getAdminCate();
        $this->assign('list',$list);
        $this->display('Barboss:apply');
    } 

    public function doapply()
    {
        if(!IS_POST){
            $this->error('数据传入非法');
        }

        $Bar = D("Bar"); // 实例化User对象
        if (!$Bar->create()){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            $this->error($Bar->getError());
        }else{
            // 验证通过 可以进行其他数据操作

            $config = array(
                'maxSize' => 3145728,
                'rootPath' => './Upload/img/tieba/',
                'saveName' => array('uniqid',''),
                'exts' => array('jpg', 'gif', 'png', 'jpeg'),
                'autoSub' => true,
                'subName' => array('date','Ymd'),
                );
            $upload = new \Think\Upload($config);// 实例化上传类
            // 上传单个文件
            $info = $upload->uploadOne($_FILES['picname']);
            if(!$info) {// 上传错误提示错误信息
                $this->error($upload->getError());
            } else {// 上传成功 获取上传文件信息
                $path = $info['savepath'].$info['savename'];
                $image = new \Think\Image();
                $image->open("./Upload/img/tieba/".$path);
                // 按照原图的比例生成一个最大为90*90的缩略图并保存为thumb.jpg
                $path = time().$info['savename'];
                $image->thumb(100, 100)->save('./Upload/img/tieba-thumb/'.$path);
            }


            $Bar->name = rtrim(I('post.name'), '吧').'吧';
            $Bar->uid = session('home_user.id');
            $Bar->picname = $path;
            // $Bar->typeid = I('post.typeid');
            // $Bar->descr = I('post.descr');
            if($Bar->add()){
                $this->success('贴吧申请成功');
            }else{
                $this->error('贴吧申请失败');
            }
        }
    }
}
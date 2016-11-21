<?php  
namespace Admin\Controller;
use Think\Controller;

class CarouselController extends AdminController
{
    public function index()
    {
        $this->assign('title', '轮播图管理');
        $this->assign('part', '轮播图');

        $data = M('Carousel')->select();
        $this->assign('list', $data);
        $this->assign('num', count($data));
        $this->display();
    }

    /**
    * 图片禁用
    * @access public        
    * @return void
    */
    public function stop()
    {
        if (IS_AJAX) {
            $data['state'] = 0;
            $user = M('Carousel')->where('id='.I('post.id'))->save($data);
            if ($user == false) {
                echo '禁用失败';
                exit;
            }
        }
        
    }

    /**
    * 图片开启
    * @access public        
    * @return void
    */
    public function start()
    {
        if (IS_AJAX) {
            $data['state'] = 1;
            $user = M('Carousel')->where('id='.I('post.id'))->save($data);
            if ($user == false) {
                echo '启用失败';
                exit;
            }
        }
        
    }


    public function addview()
    {
      $data = M('bar')->select();
      $this->assign('list',$data);
      $this->display();
    }

     /**
    * 执行添加图片
    * @access public        
    * @return void
    */
     public function doadd()
     {
      // 得到数据
      $Carousel=M('carousel');
      if(!$Carousel->create()) {
        if(IS_AJAX){
          $this->ajaxReturn($Carousel->getError());
        }else{
          $this->error($Carousel->getError());
        }
      } else {
        if(!IS_AJAX) {

          if($_FILES['picname']['name']!=''){
            $config = array (
                'maxSize' => 3145728,
                'rootPath' => './Upload/img/tieba/',
                'saveName' => array('uniqid',''),
                'exts' => array('jpg', 'gif', 'png', 'jpeg'),
                'autoSub' => true,
                'subName' => array('date','Ymd'),
              );
            $upload = new \Think\Upload($config);// 实例化上传类
            //上传单个文件
            $info = $upload->uploadOne($_FILES['picname']);
            if(!$info) {
              // 上传错误提示错误信息
              $this->error($upload->getError());
            } else {
              // 上传成功 获取文件信息
              $path = $info['savepath'].$info['savename'];
              $image = new \Think\Image();
              $image->open("./Upload/img/tieba/".$path);
              // 按照原图的比例生成一个最大为350*235的缩略图并保存为thumb.jpg
              $path = time().$info['savename'];
              $image->thumb(350, 235)->save('./Upload/img/tieba-thumb/'.$path);
            }
          }

          if($path != '') {
            $barname=I('post.barname');
            $bid=M('bar')->where("name='$barname'")->field('id')->find();
            // var_dump($bid);die;
            $data['bid'] = $bid['id'];
            $data['picname'] = $path;
            $data['state'] = I('post.state');

            // 执行添加
            if($Carousel->add($data) >0) {
              $this->success('添加成功',U('index'));
            } else {
              $this->error('添加失败');
            }
          } else {
            $this->error('请上传图片');
          }
        }
     }
  }

public function carouselShow()
    {
      $data = M('bar')->where('id='.I('get.id'))->find();
      $typeid=$data['typeid'];
      $t = M('type')->where("id='$typeid'")->field('name')->find();
      $typename=$t['name'];
      // var_dump($t);
      $this->assign('list',$data);
      $this->assign('typename',$typename);
      $this->display('carousel-show');
    }

    //删除
    public function del()
    {
      //实例化
      $carousel=M('carousel');
      // 执行删除
      if($carousel->where('id='.I('get.id'))->delete()>0){
        $this->success("删除成功！",U('Carousel/index'));
      }else{
        $this->error("删除失败");
      }
    }


}


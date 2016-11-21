<?php  
namespace Home\Controller;
use Think\Controller;

class RegisterController extends HomeController
{
    public function index()
    {
        $this->display();
    }

    /**
     * 验证码
     * @access public        
     * @return void
     */
    public function getVerify()
    {
        $config = array(
            'length'        => 1,           // 验证码位数
            'fontSize'      => 20,          // 验证码字体大小（像素）
            'imageW'        => 160,         // 验证码宽度
            'imageH'        => 35,          // 验证码高度 
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }

    public function doadd()
    {   
        $User = D("User"); // 实例化User对象
        if (!$User->create()){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            if(IS_AJAX){    
                $this->ajaxReturn($User->getError());
            }else{
                $this->error($User->getError());
            }
        }else{
            // 验证通过 可以进行其他数据操作
            if(!IS_AJAX){
                $id = $User->add();
                if($id){
                    $data['uid'] = $id;
                    $rid = M('role')->field('id')->where(array('name'=>array('eq', '普通用户')))->find();
                    $data['rid'] = $rid['id'];

                    M('user_role')->add($data);
                    $this->success('添加成功', U('Login/index'));
                }else{
                    $this->error('添加失败');
                }
            }
        }
    }
}

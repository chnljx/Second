<?php
namespace Home\Controller;
use Think\Controller;

class LoginController extends HomeController 
    {
    public function index() {
        $this->display();
    }

    public function reginster() {
    
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
            'fontSize'      => 23,          // 验证码字体大小（像素）
            'imageW'        => 205,         // 验证码宽度
            'imageH'        => 48,          // 验证码高度 
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }


    /**
     * ajax验证
     * @access public        
     * @return void
     */
    public function doLogin()
    {  
      // var_dump($_POST);
        $User = D("Login"); // 实例化User对象
        if (!$User->create()){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            if(IS_AJAX){
                $this->ajaxReturn($User->getError());
            }else{
                $this->error($User->getError(), U('Login/index'));
            }
        }else{
            // 验证通过 可以进行其他数据操作
            if(!IS_AJAX){
                $map = [];
                $map['name|phone'] = I('post.phone');
                $map['password'] = md5(I('post.password'));
                $data = $User->where($map)->find();

                // 如果手机号和密码匹配则进入，否则显示错误
                if ($data && $data['state'] == 1) {

                    

                    // $data['loginnum'] += 1;
                    // session('home_user', $data);

                    // // 登录次数自增
                    // $User->where(session('admin_user.id'))->setInc('loginnum', 1);
                    // // 更新登录时间和IP
                    // $last['lastdate'] = time();
                    // $last['lastip'] = get_client_ip();
                    // $User->where(session('admin_user.id'))->save($last);

                    // //根据用户id获取对应的节点信息
                    // $list = M('node')->field('mname,aname')->where('id in'.M('role_node')->field('nid')->where("rid in ".M('user_role')->field('rid')->where(array('uid'=>array('eq',$data['id'])))->buildSql())->buildSql())->select();

                    // //控制器名转换为大写
                    // foreach ($list as $key => $val) {
                    //     $list[$key]['mname'] = ucfirst($val['mname']);
                    // }

                    //查看查询出来的信息
                    // V($list); exit;

                    // $nodelist = array();
                    // $nodelist['Index'] = array('index','desktop');
                    // $nodelist['Logout'] = array('index','desktop');
                    // //遍历重新拼装
                    // foreach($list as $v){
                    //     $nodelist[$v['mname']][] = $v['aname'];
                    //     //把修改和执行修改 添加和执行添加 拼装到一起
                    //     if($v['aname']=="edit"){
                    //         $nodelist[$v['mname']][]="save";
                    //     }
                    //     if($v['aname']=="add"){
                    //         $nodelist[$v['mname']][]="doadd";
                    //     }
                    // }

                    //将权限信息放置到session中
                    // $_SESSION['admin_user']['nodelist'] = $nodelist;

                    //重组的信息
                    // V($_SESSION);exit;

                    $this->redirect('Index/index');
                } else {
                    $this->error('手机号或密码错误');
                }
                
            }
        }
    }
}

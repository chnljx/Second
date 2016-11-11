<?php  
namespace Admin\Model;
use Think\Model;

class UserModel extends Model{
    protected $_validate = array(
        // array(验证字段2,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('verify','require','104'),    // 验证码不能为空
        array('verify','check_verify','105',0,'function'), // 验证验证码
        array('name','require','101'),  // 101用户名/邮箱不能为空
        array('passwd','require','103'),    // 103密码不能为空
        array('name','check_name','102',0,'callback'), // 102帐号或密码错误
        array('passwd','check_passwd','102',0,'callback'),
    );

    protected function check_name($param)
    {
        $User = M('User');

        $map = [];
        $map['name|email'] = $param;
        $data = $User->where($map)->find();
        if($data){
            return true;
        }else{
            return false;
        }
    }

    protected function check_passwd($param)
    {
        $User = M('User');

        $map = [];
        $map['passwd'] = md5($param);
        $data = $User->where($map)->find();
        if($data){
            return true;
        }else{
            return false;
        }
    }
}
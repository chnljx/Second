<?php  
namespace Admin\Model;
use Think\Model;

class UserModel extends Model{
    protected $_validate = array(
        // array(验证字段2,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('verify','require','请您填写验证码'),
        array('verify','check_verify','验证码不正确',0,'function'), 
        array('name','require','请您填写用户名/邮箱'),
    );
}
<?php  
namespace Admin\Model;
use Think\Model;

class LoginModel extends Model
{   
    protected $tableName = 'user';

    protected $_validate = array(
        // array(验证字段2,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('verify','require','请您填写验证码'),
        array('verify','check_verify','验证码错误',0,'function'),
        array('name','require','请您填写帐号'),
        array('passwd','require','请您填写密码'),
    );
}

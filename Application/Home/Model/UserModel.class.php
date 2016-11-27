<?php  
namespace Home\Model;
use Think\Model;

class UserModel extends Model
{   
    protected $_validate = array(
        // array(验证字段2,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('verify','require','请您填写验证码'),
        array('verify','check_verify','验证码错误',0,'function'),
        array('name','require','请您填写用户名'),
        array('name','','用户名已经存在',0,'unique',1),
        array('passwd','require','请您填写密码'),
        array('passwd', '6,14', '密码长度不正确', 0, 'length'),
        array('repasswd', 'require', '请您填写确认密码'),
        array('repasswd', 'passwd', '两次密码输入不一致', 0, 'confirm'),
        array('email', 'require', '请您填写邮箱'),
        array('email', 'email', '邮箱格式不正确'),
        array('phone', 'require', '请您填写手机号'),
        array('phone', '/^1[34578]{1}\d{9}$/', '手机格式不正确'),
        array('code', '/^[0-9]{6}$/', '请正确填写您的邮政编码', 2),
        array('descr', '1,100', '超出范围', 2, 'length', 2),
    );

    protected $_auto = array (
        // array(完成字段1,完成规则,[完成条件,附加规则]),
        array('passwd', 'md5', 3, 'function') , // 对password字段在新增和编辑的时候使md5函数处理
        array('regtime', 'time', 1, 'function'), // 对regtime字段在新增数据的时候写入当前时间戳
        array('validate', 'uniqid', 1, 'function'),
    );
}

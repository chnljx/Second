<?php  
namespace Admin\Model;
use Think\Model;

class UserEditModel extends Model{
    protected $tableName = 'user';

    protected $_validate = array(
        // array(验证字段2,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('phone', 'require', '请您填写手机'),
        array('phone', '/^1[34578]{1}\d{9}$/', '手机格式不正确'),
        array('email', 'require', '请您填写邮箱'),
        array('email', 'email', '邮箱格式不正确'),
        array('code', '/^[0-9]{6}$/', '请正确填写您的邮政编码', 2),
    );
}

<?php  
namespace Admin\Model;
use Think\Model;

class RoleModel extends Model
{   
    protected $_validate = array(
        // array(验证字段2,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('name', 'require', '请您填写角色名'),
        array('name','','角色名称已经存在',0,'unique',1),
    );
}
<?php  
namespace Home\Model;
use Think\Model;

class BarbossModel extends Model
{
    protected $tableName = 'bar';
    
    protected $_validate = array(
        // array(验证字段2,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('descr', '10,50', '简介长度不正确', 0, 'length'),
    );
}
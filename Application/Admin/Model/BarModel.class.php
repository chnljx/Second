<?php  
namespace Admin\Model;
use Think\Model;

class BarModel extends Model{
    
    protected $_validate = array(
        // array(验证字段2,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('typeid','require','请选择贴吧类别'),
        array('name','require','请输入贴吧名称'),
        array('name','','该贴吧已经存在',0,'unique',1),
        array('descr', '10,50', '简介长度不正确', 0, 'length'),
    );

     protected $_auto = array (
        // array(完成字段1,完成规则,[完成条件,附加规则]),
        array('ctime', 'time', 3, 'function'), // 对ctime字段写入当前时间戳
    );
}
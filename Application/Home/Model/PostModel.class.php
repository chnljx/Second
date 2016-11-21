<?php  
namespace Home\Model;
use Think\Model;

class PostModel extends Model{
    
    protected $_validate = array(
        // array(验证字段2,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('title','require','请填写标题'),
        array('descr','require','请输入发表的内容'),
        
    );

     protected $_auto = array (
        // array(完成字段1,完成规则,[完成条件,附加规则]),
        array('ctime', 'time', 3, 'function'), // 对ctime字段写入当前时间戳
    );
}
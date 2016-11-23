<?php  
namespace Home\Model;
use Think\Model;

class BarModel extends Model
{   
    protected $_validate = array(
        // array(验证字段2,验证规则,错误提示,[验证条件,附加规则,验证时间])
        array('name','require','请填写贴吧名称'),
        array('name','','贴吧名称已经存在',0,'unique',1),
        array('typeid','require','请选择贴吧类型'),
        array('descr','require','请填写贴吧描述'),
        array('picname','require','请上传贴吧头像'),
    );

    protected $_auto = array (
        // array(完成字段1,完成规则,[完成条件,附加规则]),
        array('ctime', 'time', 1, 'function'), // 对regtime字段在新增数据的时候写入当前时间戳
    );
}
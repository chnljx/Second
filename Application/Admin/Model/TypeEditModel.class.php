<?php
namespace Admin\Model;
use Think\Model;

class TypeEditModel extends Model
{
    protected $tableName = 'type';
	//自动验证
	protected $_validate = array (
		array('name','require','分类名不能为空'), //新增或修改时判断name格式
		);
}

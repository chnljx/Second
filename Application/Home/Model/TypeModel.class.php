<?php 
namespace Home\Model;

use \Think\Model;

class TypeModel extends Model{
    protected $_validate = array(
      array('name','require','分类名不能为空'), //新增或修改时判断username格式
    );

    protected $_auto = array ( 
        array('path','getPath',1,'callback'),  
    );

    //获取路径的方法
    protected function getPath(){
        //获取父类path和id
        $list = $this->field('id,path')->find(I('pid'));
        if($list){
            return $list['path'].$list['id'].',';
        }else{
            return "0,";
        }
    }

    /**
     * 获取 二维数组
     * @param  integer $id    分类ID
     * @param  boolean $field 查询字段
     * @return array          分类二维数组
    */
    public function getAdminCate($id=0, $field=true){
        //获取所有分类信息
        $list = $this->field($field)->select();

        //处理分类信息
        $list =  $this->getHtmlCate($list, '★', $id, 0);
       // V($list);
        //返回
        return $list;
    }

    /**
     * 获取分类树，指定分类则返回指定分类极其子分类，不指定则返回所有分类树
     * @param  integer $id    分类ID
     * @param  boolean $field 查询字段
     * @return array          分类树
     */
    public function getHomeCate($id=0, $field=true){
        //获取所有分类信息
        $list = $this->field($field)->select();
        //处理分类信息
        $list =  $this->getTreeCate($list, $id, 'child');
        //如果 存在id
        if (is_numeric($id) && $id > 0) {
            $arr = $this->field($field)->find($id);
            $arr['child'] = $list;
            return $arr;
        }

        $list =  $this->getHtmlCate($list, '★', $id, 0);

       // V($list);
        //返回
        return $list;
    }

    /**
     * [unlimitCate 得到经过排序的分类二维数组,用于后台分级显示]
     * @param  [type]  $cate  [结果集]
     * @param  string  $html  [分隔符]
     * @param  integer $pid   [pid]
     * @param  integer $level [级别]
     * @return [type]         [二维数组]
     */
    private function getHtmlCate($cate,$html="——",$pid=0, $level=0){
        //定义空数组
        $arr = array();
        //遍历数组
        foreach($cate as $v){
            if($v['pid'] == $pid){
                $v['html'] = str_repeat($html, $level);
                $v['level'] = $level;
                $arr[] = $v;
                $arr = array_merge($arr,$this->getHtmlCate($cate,$html,$v['id'],$level+1));
            }
        }

        return $arr;
    }


    /**
     * [menu 处理返回多维分类数组,用于前台导航显示]
     * @param  [type] $cate [结果集]
     * @param  [type] $pid  [当前分类的id]
     * @param  [str] $name  [子类的索引名]
     * @return [type]       [description]
     */
    private function getTreeCate($cate,$pid=0,$name="child"){
          $arr = array();
          //遍历
          foreach($cate as $v){
            if($v['pid'] == $pid){
                $v[$name] = $this->getTreeCate($cate,$v['id']);
                $arr[] = $v;
            }
          }
          return $arr;
    }

	
    //以下 是分类的另一种方式==============================================
    /**
     * 获取分类详细信息
     * @param  milit   $id 分类ID或标识
     * @param  boolean $field 查询字段
     * @return array     分类信息
      */
    public function info($id, $field = true){
        /* 获取分类信息 */
        $map = array();
        if(is_numeric($id)){ //通过ID查询
            $map['id'] = $id;
        } else { //通过标识查询
            $map['name'] = $id;
        }
        return $this->field($field)->where($map)->find();
    }


	/**
     * 获取分类树，指定分类则返回指定分类极其子分类，不指定则返回所有分类树
     * @param  integer $id    分类ID
     * @param  boolean $field 查询字段
     * @return array          分类树
     */
    public function getTree($id = 0, $field = true){
        /* 获取当前分类信息 */
        if($id){
            $info = $this->info($id);
            $id   = $info['id'];
        }
        /* 获取所有分类 */
        //$map  = array('status' => array('gt', -1));
        $map = array();
        $list = $this->field($field)->where($map)->order('sort')->select();

        $list = list_to_tree($list, $pk = 'id', $pid = 'pid', $child = 'child', $root = $id);


        /* 获取返回数据 */
        if(isset($info)){ //指定分类则返回当前分类极其子分类
            $info['child'] = $list;
        } else { //否则返回所有分类
            $info = $list;
        }

       // V($info);

        return $info;
    }

}


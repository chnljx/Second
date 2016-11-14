<?php 
namespace Admin\Model;

use \Think\Model;

class TypeModel extends Model{

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

}

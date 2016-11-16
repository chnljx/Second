<?php 
namespace Admin\Model;

use \Think\Model;

class SystemModel extends Model
{
    protected $arr=[];

    // 得到根分类的信息
    public function root()
    {
        $array = $this->field('id')->where('pid=0')->select();
        if ($array) {
            foreach ($array as $v) {
                $arr = $this->field('count(b.id) count')->table('qm_bar b,qm_type t')->where('t.pid=0 and t.id=b.typeid')->select();
                $count
                child($v,$arr);
            }        
        }
    }

    /**
     * 获取 二维数组
     * @param  integer $id    分类ID
     * @return array          分类二维数组
    */
    // 计算子分类下的贴吧数 
    private function child($id,$count=[])
    {
        $data = $this->field('id')->where('pid='.$id)->select();
        if ($data) {
            $count += count($data);
            foreach ($data as $v) {
                child($v,$count);
            }
        } else {
            return $count;
        }
    }
}


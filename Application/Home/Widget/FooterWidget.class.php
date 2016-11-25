<?php
namespace Home\Widget;
use Think\Controller;
class FooterWidget extends Controller {
	public function footer()
	{
        $n = -1;
        $num = 0;
        $data = M('link')->where('state=1')->limit(16)->select();
        // var_dump($data);
        foreach ($data as $v) {
            
            if ($num%4!=0) {
                $arr[$n][] = $v;
            } else {
                $n++;
                $arr[$n][] = $v;
            }
            $num++;
        }
        $this->assign('data',$arr);
		$this->display('Public:footer');
	}
}
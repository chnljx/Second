<?php  
namespace Admin\Controller;
use Think\Controller;

class BarController extends AdminController
{
    public function index()
    {
        echo 'Bar';
    }

    public function add()
    {
       	echo 'add bar';
    }
  
  	// 创建贴吧请求
  	public function beg()
    {
       	echo 'add bar beg';
    }
}
<?php 
// 吧龄转换
function barage($param)
{   
    return date('Y') - date('Y', $param);
}

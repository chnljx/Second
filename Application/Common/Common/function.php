<?php 
// 检测输入的验证码是否正确，$code为用户输入的验证码字符串
function check_verify($code, $id = ''){
    $config = array(
        'reset'         => false,        // 验证成功后是否重置
        'expire'        => 60,          // 验证码的有效期（秒）
    );
    $verify = new \Think\Verify($config);
    return $verify->check($code, $id);
}

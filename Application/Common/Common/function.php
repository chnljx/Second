<?php 

/**
 * 检测输入的验证码是否正确，$code为用户输入的验证码字符串
 * @param mixed $code
 * @param mixed $id
 * @return boolean
 */
function check_verify($code, $id = ''){
    $config = array(
        'reset'         => false,        // 验证成功后是否重置
        'expire'        => 60,          // 验证码的有效期（秒）
    );
    $verify = new \Think\Verify($config);
    return $verify->check($code, $id);
}

/**
 * 格式化输出数组
 * @param mixed $data
 * @return null
 */
function V($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

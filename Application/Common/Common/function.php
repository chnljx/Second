<?php 

/**
 * 检测输入的验证码是否正确，$code为用户输入的验证码字符串
 * @param mixed $code
 * @param mixed $id
 * @return boolean
 */
function check_verify($code, $id = ''){
    $config = array(
        'reset' => false // 验证成功后是否重置，—————这里才是有效的。
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

/**
 * 格式化输出头衔
 * @param mixed $data
 * @return string
 */
function grade($param)
{
    if ($param >= 6000) {
        return '闻弦雅士';
    } elseif ($param >= 3000) {
        return '惊鸿侠影';
    } elseif ($param >= 2000) {
        return '寂寞高手';
    } elseif ($param >= 1000) {
        return '风云使者';
    } elseif ($param >= 500) {
        return '逍遥游侠';
    } elseif ($param >= 200) {
        return '武林新贵';
    } elseif ($param >= 100) {
        return '草莽豪杰';
    } elseif ($param >= 50) {
        return '人海孤鸿';
    } elseif ($param >= 30) {
        return '试剑江湖';
    } elseif ($param >= 15) {
        return '赏茶闲客';
    } elseif ($param >= 5) {
        return '白衣秀士';
    } else {
        return '人在旅途';
    }   
}
<?php

/**
 * 行为绑定
 */
// 用于解决跨域问题，行为绑定解决跨域问题。此前必须新建 app->api->behavior->CORS 文件，在 tags.php 文件中
// 应用初始化 'app_init' => [ 'app\\api\\behavior\\CORS']。
// 至此可完美解决跨域问题
\think\Hook::add('app_init','app\\api\\behavior\\CORS');

///**
// * 返回对象
// * @param $array 响应数据
// */

// 返回一个数组到前端，可以用其作为判断条件
function resultArray($array)
{
    if(isset($array['data'])) {
        $array['error'] = '';
        $code = 200;
    } elseif (isset($array['error'])) {
        $code = 400;
        $array['data'] = '';
    }
    return [
        'code'  => $code,
        'data'  => $array['data'],
        'error' => $array['error']
    ];
}

/**
 * 调试方法
 * @param  array   $data  [description]
 */
function p($data,$die=1)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    if ($die) die;
}

/**
 * 用户密码加密方法
 * @param  string $str      加密的字符串
 * @param  [type] $auth_key 加密符
 * @return string           加密后长度为32的字符串
 */
function user_md5($str, $auth_key = '')
{
    return '' === $str ? '' : md5(sha1($str) . $auth_key);
}



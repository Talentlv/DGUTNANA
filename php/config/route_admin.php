<?php
// +----------------------------------------------------------------------
// | Description: 基础框架路由配置文件
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honghaiweb.com>
// +----------------------------------------------------------------------
use think\Route;

// 验证Token-找回密码
// 点击重置密码链接后的路由配置
// 参数说明：rule: 操作方法（是否带参数），route: 模块名称/控制器名称/控制器的（行为）方法
Route::rule('verifyToken/:token','admin/users/verifyToken');

return [
    // 定义资源路由
    '__rest__'=>[
        'admin/rules'		   =>'admin/rules',
        'admin/groups'		   =>'admin/groups',
        'admin/users'		   =>'admin/users',
        'admin/posts'          =>'admin/posts',
    ],

    // 用户登录
    'admin/users/login' => ['admin/users/login', ['method' => 'POST']],
    // 记住密码 登录
    'admin/users/relogin'	=> ['admin/users/relogin', ['method' => 'POST']],
    // 用户注册
    'admin/users/register' => ['admin/users/register', ['method' => 'POST']],
    // 验证用户名-找回密码
    'admin/users/findPwd' => ['admin/users/findPwd', ['method' => 'POST']],
    // 验证邮箱-找回密码
    'admin/users/findEmail' => ['admin/users/findEmail', ['method' => 'POST']],
    // 验证Token-找回密码
//    'verifyToken/:token' => ['admin/users/verifyToken', ['method' => 'GET']],
    // 重置密码-找回密码
    'admin/users/resetPwd' => ['admin/users/resetPwd', ['method' => 'POST']],
    // 退出登录
    'admin/users/logout' => ['admin/users/logout', ['method' => 'POST']],
    // 获取用户基本信息
    'admin/users/getUserMsg' => ['admin/users/getUserMsg', ['method' => 'POST']],
    // 获取用户主要信息
    'admin/users/getUserData' => ['admin/users/getUserData', ['method' => 'POST']],
    // 获取验证码
    'admin/users/getVerify' => ['admin/users/getVerify', ['method' => 'GET']],
    // 用户头像上传
    'admin/users/uploadImg' => ['admin/users/uploadImg', ['method' => 'POST']],
    'admin/users/uploadFile' => ['admin/users/uploadFile', ['method' => 'POST']],

    // 保存用户头像图片地址
    'admin/users/saveImg' => ['admin/users/saveImg', ['method' => 'POST']],

    // Home 模块
    // 获取各模块信息
    'admin/menus/getAllMsg' => ['admin/menus/getAllMsg', ['method' => 'GET']],

    // 保存文章
    'admin/articles/articleSend' => ['admin/articles/articleSend', ['method' => 'POST']],
    // 读取文章
    'admin/articles/getArticleMsg' => ['admin/articles/getArticleMsg', ['method' => 'POST']],
    // 读取文章所属版面
    'admin/articles/getArticlePart' => ['admin/articles/getArticlePart', ['method' => 'POST']],
    // 读取文章所属版面
    'admin/articles/getUserBase' => ['admin/articles/getUserBase', ['method' => 'POST']],

    // 保存文章评论
    'admin/articles/commentSend' => ['admin/articles/commentSend', ['method' => 'POST']],
    
    // 读取文章所有评论
    'admin/articles/getAllComs' => ['admin/articles/getAllComs', ['method' => 'POST']],
    // 获取当前版面信息
    'admin/articles/getPartMsg' => ['admin/articles/getPartMsg', ['method' => 'POST']],
    // 获取当前版面文章信息
    'admin/articles/getArticleData' => ['admin/articles/getArticleData', ['method' => 'POST']],
    

    // MISS路由
    // '__miss__'  => 'admin/users/miss',
];
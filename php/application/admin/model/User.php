<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/2/23 0023
 * Time: 16:40
 */

namespace app\admin\model;

use think\Model;
//use think\Session;
use think\Db;
use com\verify\HonrayVerify;
use com\Smtp;

class User extends Model
{
    protected $table = 'tp5_user';  //指定数据表名
    protected $pk = 'Id';       	//指定主键的字段

    // 自动插入用户状态 status=1
    protected $insert = [
        'status' => 1
    ];


    // 用户登录
    /**
     * [login 登录]
     * @AuthorHTL
     * @DateTime  2017-02-10T22:37:49+0800
     * @param     [string]                   $u_username [账号]
     * @param     [string]                   $u_pwd      [密码]
     * @param     [string]                   $verifyCode [验证码]
     * @param     Boolean                  	 $isRemember [是否记住密码]
     * @param     Boolean                    $type       [是否重复登录]
     * @return    [type]                               [description]
     */
    public function login($username, $password, $verifyCode = '', $isRemember = false, $type = false){
        if (!$username) {
            $this->error = '帐号不能为空';
            return false;
        }
        if (!$password){
            $this->error = '密码不能为空';
            return false;
        }
        // 这里需要做 自动登录 处理，自动登录则说明不需要验证码
        if (!$type){
            if (!$verifyCode && !$type) {
                $this->error = '验证码不能为空';
                return false;
            }
            $captcha = new HonrayVerify(config('captcha'));
            if (!$captcha->check($verifyCode)) {
                $this->error = '验证码错误';
                return false;
            }
        }

        $map = $username;
        $userInfo = $this->where('username',$map)->find();
        if (!$userInfo){
            $this->error = '用户名不存在';
            return false;
        }
        if (user_md5($password) !== $userInfo['password']){
            $this->error = '密码错误';
            return false;
        }
        if ($userInfo['status'] === 0){
            $this->error = '账号已被禁用';
        }

        // 以后 admin 表还有个权限管理

        // 是否记住密码 或 重复登录
        if ($isRemember || $type){
            $secret['username'] = $username;
            $secret['password'] = $password;
            // 进行加密，加密函数 encrypt()
            $data['rememberKey'] = encrypt($secret);
        }

        session_start();
        // $info 数组存储用户信息以及 sessionId，以及它们的加密
        $info['userInfo'] = $userInfo;
        $info['sessionId'] = session_id();

        // 权限认证
        // $authKey = user_md5($userInfo['username'].$userInfo['password'].$info['sessionId']);
        $authKey = encrypt($userInfo['username']);
        $info['authKey'] = $authKey;
        // 缓存数据到本地
        cache('Auth_'.$authKey, null);
        cache('Auth_'.$authKey, $info, 3600);

        // 返回数据（到前端）
        $data['authKey']		= $authKey;
        $data['sessionId']		= $info['sessionId'];
        $data['userInfo']		= $userInfo;

        return $data;
    }

}
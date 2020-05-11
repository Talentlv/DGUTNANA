<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/2/23 0023
 * Time: 17:13
 */

namespace app\admin\controller;

use app\common\controller\Common;
use com\verify\HonrayVerify;
use app\admin\model\User;
use think\Request;
use think\Db;

class Users extends Common
{
    //用户登录
    public function login(){
        $param = Request::instance()->post();

        $username = $param['username'];
        $password = $param['password'];
        $verifyCode = $param['verifyCode'];
        $isRemember = !empty($param['isRemember'])? $param['isRemember']: '';

        $userModel = new User();
        // 返回的是一个数组($data)
        $result = $userModel->login($username, $password, $verifyCode, $isRemember);

        if (!$result){
            return resultArray(['error' => $userModel->getError()]);
        }
        return resultArray(['data' => $result]);

    }
    // 记住密码的用户登录(自动登录)
    public function relogin(){
        $param = Request::instance()->post();

        $data = decrypt($param['rememberKey']);
        $username = $data['username'];
        $password = $data['password'];

        $userModel = new User();
        $result = $userModel->login($username, $password, '', true,true);

        if (!$result){
            return resultArray(['error' => $userModel->getError()]);
        }
        return resultArray(['data' => $result]);
    }
    // 退出登录
    public function logout(){
        $param = Request::instance()->post();

        cache('Auth_'.$param['authkey'], null);
        return resultArray(['data'=>'退出成功']);
    }

    // 获取用户基本信息
    public function getUserMsg(){
        $param = Request::instance()->post();
        $username = decrypt($param['username']);

        $userModel = new User();
        $userInfo = $userModel->where('username',$username)->find();

        $user_id = $userInfo['Id'];

         // 发帖数
         $result1 = Db::name('articles')->where('user_id',$user_id)->select();
         $count1 = count($result1);
         // 热门帖子数
         $result2 = Db::name('articles')->where('user_id',$user_id)->where('iselite',1)->select();
         $count2 = count($result2);
 
         $userInfo['invitations'] = $count1;
         $userInfo['hots'] = $count2;

        if ($userInfo){
            return resultArray(['data' => $userInfo]);
        }else{
            return resultArray(['error' => '获取信息失败']);
        }
    }
    // 获取用户主要信息
    public function getUserData(){
        $params  = Request::instance()->post();

        $user_id = $params['user_id'];

        // find() 找一条数据
        $res = Db::name('user')->where('Id',$user_id)->find();

        $data['username'] = $res['username'];
        $data['nickname'] = $res['nickname'];
        $data['email'] = $res['email'];
        $data['gender'] = $res['gender'];
        $data['birthday'] = $res['birthday'];
        $data['img_url'] = $res['img_url'];

        // select(),数据集
        // 发帖数
        $result1 = Db::name('articles')->where('user_id',$user_id)->select();
        $count1 = count($result1);
        // 热门帖子数
        $result2 = Db::name('articles')->where('user_id',$user_id)->where('iselite',1)->select();
        $count2 = count($result2);
        // 评论数
        $result3 = Db::name('comments')->where('from_uid',$user_id)->select();
        $count3 = count($result3);

        $data['invitations'] = $count1;
        $data['hots'] = $count2;
        $data['comments'] = $count3;

        // 负责版面
        $result4 = Db::name('parts')->where('user_id',$user_id)->find();
        $data['duty'] = $result4['name'];

        if($data) {
            return resultArray(['data' => $data]);
        }else{
            return resultArray(['error' => '获取用户主要信息失败']);
        }

    }
    // 上传用户头像
    public function uploadImg(Request $request){
        $file = $request->file('file');

        $url = ROOT_PATH.'public'.DS.'upload/avatars';
        $dirName = date('Ymd');

        // 服务器上要设置写入的权限并判断目录是否存在，不存在则要先创建目录
        // ROOT_PATH => 框架应用根目录，此处为php
        if(!file_exists(ROOT_PATH.'public'.DS.'upload/avatars/'.$dirName)){
            mkdir(ROOT_PATH.'public'.DS.'upload/avatars/'.$dirName);
        }

        $info = $file->move($url);
        if ($info){
            // $imgUrl = $_SERVER['HTTP_HOST'] == 'localhost' ? 'http://'.$_SERVER['HTTP_HOST'].'\/'.explode('/',$_SERVER['SCRIPT_NAME'])[1].'\static\upload\avatar\/'.$info->getSaveName() : $_SERVER['HTTP_HOST'].'/static/upload\avatar/'.$info->getSaveName();
            // $_SERVER['HTTP_HOST'] = 'vt.com'
            // $imgUrl = http://vt.com/php/public/upload/avatars/20200313/e8f86c129d9ba51273f2e165e0f832e7.jpg

            $imgUrl = 'http://'.$_SERVER['HTTP_HOST'].'/php/public/upload/avatars/'.$info->getSaveName();

            return resultArray(['data' => $imgUrl]);

        }else{
            return resultArray(['error' => $file->getError()]);
        }
    }
    // 保存头像图片地址
    public function saveImg(){
        $param = Request::instance()->post();
        $username = $param['username'];
        $imgUrl = $param['imageUrl'];

        $userModel = new User();

        $result = $userModel->where('username', $username)->update(['img_url' => $imgUrl]);
        if ($result){
            return resultArray(['data' => '保存图片地址成功']);
        }else{
            return resultArray(['error' => $userModel->getError()]);
        }

    }
    //
    public function uploadFile(Request $request){
        $file = $request->file('file');

        $url = ROOT_PATH.'public'.DS.'upload/articlesImgs';
        $dirName = date('Ymd');

        // 服务器上要设置写入的权限并判断目录是否存在，不存在则要先创建目录
        // ROOT_PATH => 框架应用根目录，此处为php
        if(!file_exists(ROOT_PATH.'public'.DS.'upload/articlesImgs/'.$dirName)){
            mkdir(ROOT_PATH.'public'.DS.'upload/articlesImgs/'.$dirName);
        }

        $info = $file->move($url);

        if ($info){
            // $imgUrl = $_SERVER['HTTP_HOST'] == 'localhost' ? 'http://'.$_SERVER['HTTP_HOST'].'\/'.explode('/',$_SERVER['SCRIPT_NAME'])[1].'\static\upload\avatar\/'.$info->getSaveName() : $_SERVER['HTTP_HOST'].'/static/upload\avatar/'.$info->getSaveName();
            // $_SERVER['HTTP_HOST'] = 'vt.com'
            // $imgUrl = http://vt.com/php/public/upload/avatars/20200313/e8f86c129d9ba51273f2e165e0f832e7.jpg

            $imgUrl = 'http://'.$_SERVER['HTTP_HOST'].'/php/public/upload/articlesImgs/'.$info->getSaveName();

            return resultArray(['data' => $imgUrl]);

        }else{
            return resultArray(['error' => $file->getError()]);
        }
    }
    // 用户注册
    public function register()
    {
        $param = Request::instance()->post();

        $map1 = $param['num'];
        $map2 = $param['username'];
        $userModel = new User();

        $userInfo1 = $userModel->where('num',$map1)->find();
        $userInfo2 = $userModel->where('username',$map2)->find();

        if ($userInfo1){
            return resultArray(['error' => '该学号已经注册，请登录']);
        }
        if ($userInfo2){
            return resultArray(['error' => '该用户名已被占用']);
        }

        $userModel->num = $param['num'];
        $userModel->username = $param['username'];
        $userModel->nickname = $param['nickname'];
        $userModel->password = user_md5($param['password']);
        $userModel->email = $param['email'];
        $userModel->gender = $param['gender'];
        $userModel->birthday = $param['birthday'];
        $userModel->creat_time = date('Y:m:d H:i:s',time());

        $result = $userModel->save();
        if (!$result){
            return resultArray(['error' => '注册失败']);
        }
        else{
            return resultArray(['data' => $result]);
        }
    }
	
	// 验证用户名-找回密码
	public function findPwd()
	{
		$param = Request::instance()->post();
		
		$map = $param['username'];
		
		$userModel = new User();
		$result = $userModel->where('username', $map)->find();
		
		if(!$result){
			return resultArray(['error' => '该用户名不存在']);
		}else{
			return resultArray(['data' => $result]);
		}
	}

	// 验证邮箱-找回密码
	public function findEmail()
	{
		$param = Request::instance()->post();

		$map = $param['email'];

		$userModel = new User();
		$result = $userModel->where('email', $map)->find();

        if (!$result){
            return resultArray(['error' => '邮箱错误']);
        }

		// 获取用户信息
		$num = $result['num'];
		$username = $result['username'];
		$email = $result['email'];

		// 信息加密
		$secret['username'] = $username;
		$secret['email'] = $email;

		// 生成 token
		$token = encrypt($secret);

		$creatTime = time();
		$expiryTime = $creatTime + 2*60*60;		// 有效时间为2小时

		// 往数据表 findpwd 添加一条信息
		$data['num'] = $num;
		$data['username'] = $username;
		$data['token'] = $token;
		$data['email'] = $email;
		$data['creatTime'] = $creatTime;
		$data['expiryTime'] = $expiryTime;
		$data['isExpiried'] = 0;

		Db::name('findpwd')->insert($data);

		$url = 'http://vt.com/php/index.php/verifyToken/'.$token;
        $res = sendEmail($username, $email, $url);


        if ($res){
            return resultArray(['data' => '发送成功']);
        }else{
            return resultArray(['error' => '发送失败']);
        }
	}
	
	// 验证token-找回密码
    public function verifyToken($token){
        $res =$token;
        // $token = Request::instance()->param('token');
        $data = decrypt($res);
        $username = $data['username'];
        $email = $data['email'];

        $res = Db::name('findpwd')->where('username',$username)->find();
        if ($res && $res['email'] === $email && $res['isExpiried'] === 0){
            Db::name('findpwd')->where('username',$username)->update(['isExpiried' => 1]);
            $msg = $username;
            // 原生PHP页面跳转方法，header()
            header("Location:http://localhost:8080/Resetpwd/".$msg);
        }elseif (time() - $res['expiryTime'] > 2*60*60){
            $msg = '链接已失效';
            header("Location:http://localhost:8080/Resetpwd/".$msg);
        }
        else{
            $msg = '链接已失效';
            header("Location:http://localhost:8080/Resetpwd/".$msg);
        }
    }

    // 重置密码-找回密码
    public function resetPwd(){
        $param = Request::instance()->post();
        $username = $param['username'];
        $password = user_md5($param['password']);

        $userModel = new User();
        $result = $userModel->where('username',$username)->update(['password' => $password]);

        if (!$result){
            return resultArray(['error' => '重置失败']);
        }else{
            return resultArray(['data' => '重置成功']);
        }
    }

    // 获取验证码
    public function getVerify()
    {
        $captcha = new HonrayVerify(config('captcha'));
        return $captcha->entry();
    }

    
    // public function miss(){
    //     echo 'miss路由';
    // }
}
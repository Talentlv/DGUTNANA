<?php

namespace app\admin\controller;

use app\common\controller\Common;
use app\admin\model\Article;
use think\Db;
use think\Request;

use function PHPSTORM_META\type;

class Articles extends Common
{
    // 文章保存
    public function articleSend(){
        $params = Request::instance()->post();

        $articleModel = new Article();

        $username = decrypt($params['username']);
        $arr = Db::name('user')->where('username',$username)->find();
        $user_id = $arr['Id'];
        $add_time = date('Y:m:d H:i:s',time());

        // echo $user_id;
        $articleModel->user_id = $user_id;
        $articleModel->part_id = $params['part_id'];
        
        $articleModel->author = $username;
        $articleModel->title = $params['title'];
        $articleModel->content = $params['content'];
        $articleModel->add_time = $add_time;
        // echo $username;

        
        $result = $articleModel->save();
        // $result = $articleModel->saveArticle($username, $part_id, $author, $title, $content);


        // $result = Db::name('articles')->insert(['user_id'=>$user_id, 'part_id'=>$part_id, 'title'=>$title, 'content'=>$content,'author'=>$author, 'add_time'=>$add_time]);
        if ($result) {
            // 返回文章id
            return resultArray(['data' => $articleModel->article_id]);
        }else{
            return resultArray(['error' => '发送失败']);
        }  
    }

    // 获取文章内容
    public function getArticleMsg(){
        $params = Request::instance()->post();
        $article_id = $params['article_id'];

        $data = Db::name('articles')->where('article_id',$article_id)->find();

        // $user_id = $data['user_id'];
        // $data['userData'] = Db::name('user')->where('user_id',$user_id)->find();

        if ($data) {
            return resultArray(['data' => $data]);
        } else {
            return resultArray(['error' => '找不到数据']);
        }
        
    }
    // 获取文章所属版面及模块
    public function getArticlePart(){
        $params = Request::instance()->post();
        $part_id = $params['part_id'];

        $resultPart = Db::name('parts')->where('part_id',$part_id)->find();

        $data['partName'] = $resultPart['name'];

        $resultSort = Db::name('sorts')->where('sort_id',$resultPart['sort_id'])->find();

        $data['sortName'] = $resultSort['sort_part'];

        if ($data) {
            return resultArray(['data' => $data]);
        } else {
            return resultArray(['error' => '找不到数据']);
        }
        
    }
    // 获取用户关于文章的基本信息
    public function getUserBase(){
        $params  = Request::instance()->post();

        $from_uid = $params['from_uid'];
        $to_uid = $params['to_uid'];
        $content = $params['content'];
        // $before_content = $params['before_content'];
        // if(gettype($before_content) === 'undefined'){
        //     $before_content = '';
        // }

        // find() 找一条数据
        $res = Db::name('user')->where('Id',$from_uid)->find();
        
        $data['Id'] = $res['Id'];
        $data['from_username'] = $res['username'];
        $data['nickname'] = $res['nickname'];
        $data['img_url']  = $res['img_url'];
        $data['content']  = $content;
        // $data['before_content'] = $before_content;

        $res2 = Db::name('user')->where('Id',$to_uid)->find();
        $data['to_username'] =$res2['username'];

        // select(),数据集
        // 发帖数
        $result1 = Db::name('articles')->where('user_id',$from_uid)->select();
        $count1 = count($result1);
        // 热门帖子数
        $result2 = Db::name('articles')->where('user_id',$from_uid)->where('iselite',1)->select();
        $count2 = count($result2);

        $data['invitations'] = $count1;
        $data['hots'] = $count2;

        if($data) {
            return resultArray(['data' => $data]);
        }else{
            return resultArray(['error' => '获取文章基本信息失败']);
        }

    }

    // 获取当前文章所有评论
    public function getAllComs(){
        $params  = Request::instance()->post();

        $article_id = $params['article_id'];

        $result = Db::name('comments')->where('article_id',$article_id)->order('com_time desc')->select();

        if($result) {
            return resultArray(['data' => $result]);
        }else{
            return resultArray(['error' => '当前文章没有评论']);
        }
    }

    // 获取当前版面的信息
    public function getPartMsg(){
        $params  = Request::instance()->post();

        $part_id = $params['part_id'];

        // 获取版面描述
        $map1 = Db::name('parts')->where('part_id',$part_id)->find();
        $data['desc'] = $map1['desc'];

        // 获取版面新增和总帖数
        $currentDate = date('Y-m-d',time());
        $map2 = Db::name('articles')->where('part_id',$part_id)->where('add_time','like',"%".$currentDate."%")->count();
        $data['updates'] = $map2;
        $map3 = Db::name('articles')->where('part_id',$part_id)->count();
        $data['sumTies'] = $map3;

        //获取版面负责人
        $map4 = Db::name('parts')->where('part_id',$part_id)->find();
        $map5 = Db::name('user')->where('Id',$map4['user_id'])->find();
        $data['duty'] = $map5['username'];

        return resultArray(['data' => $data]);
    }

    // 获取新增的版面文章
    public function getArticleData(){
        $params  = Request::instance()->post();

        $part_id = $params['part_id'];

        $map1 = Db::name('articles')->where('part_id',$part_id)->where('article_status',0)->select();

        for ($i=0; $i < count($map1); $i++) { 
            $map1[$i]['add_time'] = date("Y-m-d",strtotime($map1[$i]['add_time']));
        }

        return resultArray(['data' => $map1]);
    }
}

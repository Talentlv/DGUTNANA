<?php

namespace app\admin\controller;

use app\common\controller\Common;
use app\admin\model\Comment;
use think\Db;
use think\Request;

class Comments extends Common
{
    public function commentSend(){
        $params = Request::instance()->post();

        $commentModel = new Comment();

        $com_time = date('Y:m:d H:i:s',time());

        $commentModel->from_uid = $params['from_uid'];
        $commentModel->to_uid = $params['to_uid'];
        $commentModel->article_id = $params['article_id'];
        $commentModel->content = $params['content'];
        $commentModel->com_time = $com_time;

        $result = $commentModel->save();

        $map = Db::name('articles')->where('article_id',$params['article_id'])->find();
        $newPostNum = $map['post_num']+1;

        $resultPlus = Db::name('articles')->where('article_id',$params['article_id'])->update(['post_num' => $newPostNum]);

        if($result && $resultPlus){
            return resultArray(['data' => $newPostNum]);
        }else{
            return resultArray(['error' => '评论失败']);
        }


    }
}
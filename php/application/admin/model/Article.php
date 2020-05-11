<?php
namespace app\admin\model;

use think\Model;
//use think\Session;
use think\Db;

class Article extends Model
{
    protected $table = 'tp5_articles';  //指定数据表名
    protected $pk = 'article_id';       	//指定主键的字段

    // 自动插入某些字段
    protected $insert = [
        'keyword' => '',
        'hits' => 0,
        'post_num' => 0,
        'iselite' => 0,
        'update_time' => '',
        'article_status' => 0
    ];

}
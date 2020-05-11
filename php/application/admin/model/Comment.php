<?php
namespace app\admin\model;

use think\Model;
//use think\Session;
use think\Db;

class Comment extends Model
{
    protected $table = 'tp5_comments';  //指定数据表名
    protected $pk = 'comment_id';       	//指定主键的字段

    // 自动插入某些字段

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/19 0019
 * Time: 12:03
 */

namespace app\admin\controller;

use app\common\controller\Common;
use DateTime;
use think\Db;

// global $a_old;

class Menus extends Common
{
    public function getAllMsg(){
        // 获取十大热门信息，
        // 查询出 iselite 为 0(推荐) 的文章
        // orderRaw("rand()") 随机选出符合条件的十条数据
        $map1 = Db::name('articles')->where('iselite',0)->orderRaw("rand()")->limit(10)->select();

        // 获取校园热点信息
        // 查询 hit(点击数) 大于15 的文章
        // orderRaw("rand()") 随机选出符合条件的十条数据
        $map2 = Db::name('articles')->where('hits','>',15)->orderRaw("rand()")->limit(10)->select();

        // 获取热门版面信息
        // 查询出 elite 为 0(推荐) 的版面
        $map3 = Db::name('parts')->where('iselite',0)->orderRaw("rand()")->limit(10)->select();

        // 获取版面菜单信息
        $map4 = Db::name('parts')->where('status',0)->select();

        $data['tenHot'] = $map1;
        $data['hot'] = $map2;
        $data['hotPart'] = $map3;
        $data['parts'] = $map4;

        // 获取每天登录网站新增的帖子数量
        $rows = Db::name('parts')->count();
        $currentDate = date('Y-m-d',time());

        for ($i = 1; $i <= $rows; $i++) { 
            $result[$i] = Db::name('articles')->where('part_id',$i)->where('add_time','like',"%".$currentDate."%")->count();
        }
        $data['updates'] = $result;

        return resultArray(['data' => $data]);
    }

    
    
}
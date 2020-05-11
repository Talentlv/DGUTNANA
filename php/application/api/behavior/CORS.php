<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/2/24 0024
 * Time: 10:51
 */

namespace app\api\behavior;
use think\Response;
class CORS
{
    public function appInit(&$params)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: token, Origin, Content-Type, Accept, X-Requested-With, Content-Type, authKey, sessionId");
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Expose-Headers: Authorization');
        if (request()->isOptions()) {
            exit();
        }
    }
}
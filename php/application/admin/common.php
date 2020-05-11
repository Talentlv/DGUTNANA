<?php
// bsf管理模板函数文件
//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\SMTP;
//use PHPMailer\PHPMailer\Exception;
//use think\Log;
use phpmailer\phpmailer;
/**
 * 给树状菜单添加level并去掉没有子菜单的菜单项
 * @param  array   $data  [description]
 * @param  integer $root  [description]
 * @param  string  $child [description]
 * @param  string  $level [description]
 */
function memuLevelClear($data, $root=1, $child='child', $level='level')
{
    if (is_array($data)) {
        foreach($data as $key => $val){
            $data[$key]['selected'] = false;
            $data[$key]['level'] = $root;
            if (!empty($val[$child]) && is_array($val[$child])) {
                $data[$key][$child] = memuLevelClear($val[$child],$root+1);
            }else if ($root<3&&$data[$key]['menu_type']==1) {
                unset($data[$key]);
            }
            if (empty($data[$key][$child])&&($data[$key]['level']==1)&&($data[$key]['menu_type']==1)) {
                unset($data[$key]);
            }
        }
        return array_values($data);
    }
    return array();
}

/**
 * [rulesDeal 给树状规则表处理成 module-controller-action ]
 * @AuthorHTL
 * @DateTime  2017-01-16T16:01:46+0800
 * @param     [array]                   $data [树状规则数组]
 * @return    [array]                         [返回数组]
 */
function rulesDeal($data)
{
    if (is_array($data)) {
        $ret = [];
        foreach ($data as $k1 => $v1) {
            $str1 = $v1['name'];
            if (is_array($v1['child'])) {
                foreach ($v1['child'] as $k2 => $v2) {
                    $str2 = $str1.'-'.$v2['name'];
                    if (is_array($v2['child'])) {
                        foreach ($v2['child'] as $k3 => $v3) {
                            $str3 = $str2.'-'.$v3['name'];
                            $ret[] = $str3;
                        }
                    }else{
                        $ret[] = $str2;
                    }
                }
            }else{
                $ret[] = $str1;
            }
        }
        return $ret;
    }
    return [];
}

/**
 * cookies加密函数
 * @param string 加密后字符串
 */
function encrypt($data, $key = 'kls8in1e')
{
    $prep_code = serialize($data);
    $block = mcrypt_get_block_size('des', 'ecb');
    if (($pad = $block - (strlen($prep_code) % $block)) < $block) {
        $prep_code .= str_repeat(chr($pad), $pad);
    }
    $encrypt = mcrypt_encrypt(MCRYPT_DES, $key, $prep_code, MCRYPT_MODE_ECB);
    return base64_encode($encrypt);
}

/**
 * cookies 解密密函数
 * @param array 解密后数组
 */
function decrypt($str, $key = 'kls8in1e')
{
    $str = base64_decode($str);
    $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
    $block = mcrypt_get_block_size('des', 'ecb');
    $pad = ord($str[($len = strlen($str)) - 1]);
    if ($pad && $pad < $block && preg_match('/' . chr($pad) . '{' . $pad . '}$/', $str)) {
        $str = substr($str, 0, strlen($str) - $pad);
    }
    return unserialize($str);
}

// 邮箱发送
function sendEmail($username, $email, $url)
{

//    vendor ('phpmailer.phpmailer.src.PHPMailer');
//    vendor ('phpmailer.phpmailer.src.SMTP');
    $mail = new PHPMailer();
    //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
    $mail->SMTPDebug = 1;
    // 设置PHPMailer使用SMTP服务器发送Email
    $mail->IsSMTP();
    // 163邮箱的ssl协议方式端口号是465/994
    $mail->Port = 465;
    // 设置邮件的字符编码，若不指定，则为'UTF-8'
    $mail->CharSet = 'UTF-8';
    // 添加收件人地址，可以多次使用来添加多个收件人
    $mail->addAddress($email);
    // 设置邮件正文
    $mail->Body = "尊敬的".$username."：<br/>您正在通过邮箱重置莞工娜娜的登录密码，请点击下面的链接重置密码
，如果邮箱不能跳转链接，请将该链接复制到浏览器打开。<br/><a href='".$url."' target='_blank'>".$url."</a><br />该链接有效时间为2小时";
    //设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
    $mail->From='talentlv520@163.com';
    //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
    $mail->FromName='小才';
    // 设置邮件标题
    $mail->Subject = '莞工娜娜-重置密码';
    // 设置SMTP服务器。
    $mail->Host='smtp.163.com';
    $mail->SMTPAuth = true;// 是否使用身份验证
    // 使用ssl协议方式
    $mail->SMTPSecure = "ssl";
    $mail->isHTML(true); // 设置邮件格式为HTML
    // 设置为"需要验证"
    $mail->SMTPAuth=true;
    //smtp登录的账号 这里填入字符串格式的qq号即可
    $mail->Username='talentlv520@163.com';
    //smtp登录的密码 使用生成的授权码 你的最新的授权码
    $mail->Password='dgut201314';
    // 发送邮件。    成功返回true或false
    if(!$mail->send()){// 发送邮件
        return $mail->ErrorInfo;// 输出错误信息
    }else{
        return '发送成功';
    }
}
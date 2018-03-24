<?php
/**
 * Created by PhpStorm.
 * User: sinke
 * Date: 2017/6/20
 * Time: 15:20
 */

/**
 * 枚举文件
 * @param $path
 * @return array
 */
function enumerateFiles($path){
    $list   = [];
    $dir    = @dir($path);
    while(($file=$dir->read())!==false){
        if($file != '.' && $file != '..'){
            $list[] = $file;
        }
    }
    $dir->close();
    return $list;
}

/**
 * 根据前后字符串 取出中间内容
 * @param $str
 * @param $leftStr
 * @param $rightStr
 * @return bool|string
 */
function getSubstr($str, $leftStr, $rightStr){
    $left = strpos($str, $leftStr);
    $right = strpos($str, $rightStr,$left);
    if($left < 0 or $right < $left){
        return '';
    }
    return substr($str, $left + strlen($leftStr), $right-$left-strlen($leftStr));
}

/**
 * 获取Gravatar头像链接
 * @param $email
 * @return string
 */
function getGravatar($email) {
    return '//dn-qiniu-avatar.qbox.me/avatar/'.md5(strtolower(trim($email)));
}

/**
 * 访问URL请求结果
 * @param $url 地址
 * @param string $data post数据
 * @param string $type 提交方式
 * @param string $cookie cookie
 * @param array $header 客户端header
 * @param int $cacheTime 缓存时间
 * @return array [header&content]
 */
function requestByCurl($url,$data='',$type='get',$cookie='',$header=[],$cacheTime=0){
    if($cacheTime > 0){
        //开启缓存 检查缓存是否存在
        $currentTime   = time();
        $key    = 'request.'.md5($url.$data);
        if($data = cache($key)){
            $updateTime = $data['updateTime'];
            //检查是否过期
            if($currentTime - $updateTime <= $cacheTime){
                //直接返回
                return $data['data'];
            }
        }
    }
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    if($type=='post'){
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
    }
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_COOKIE,$cookie);
    $data		= curl_exec($ch);
    $headerSize	= curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $arr        = [
        'header'    => substr($data,0, $headerSize),
        'content'   => substr($data, $headerSize)
    ];
    curl_close($ch);
    if($cacheTime > 0) {
        //如果开启缓存 更新缓存
        cache($key, ['updateTime'=>$currentTime,'data'=>$arr], $cacheTime);
    }
    return $arr;
}


/**
 * 发送邮件
 * @param array $to
 * @param string $subject
 * @param string $content
 * @return bool|void
 */
function sendMail($to=[],$subject='',$content=''){
    $mail = new Nette\Mail\Message;
    $config = new app\index\controller\Base;
    $config = $config->_K['setting'];
    if(
        empty($config['smtp_server'])
        || empty($config['smtp_port'])
        || empty($config['smtp_ssl'])
        || empty($config['smtp_mail'])
        || empty($config['smtp_password'])
    ){
        return false;
    }
    if (is_array($to)){
        foreach ($to as $v) {
            $mail->addTo($v);
        }
    } else {
        $mail->addTo($to);
    }
    $mail->setFrom($config['smtp_mail']);
    $mail->setSubject($subject);
    $mail->setHTMLBody($content);
    $mailer = new Nette\Mail\SmtpMailer([
        'host'      => $config['smtp_server'],
        'username'  => $config['smtp_mail'],
        'password'  => $config['smtp_password'],
        'port'      => $config['smtp_port'],
        'secure'    => $config['smtp_ssl'] == 'ssl' ? 'ssl' : ''
    ]);
    return $mailer->send($mail);
}
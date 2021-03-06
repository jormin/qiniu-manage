<?php

namespace common\libs;
use yii\helpers\Html;

/**
 * Class CommonFunction
 * @package common\models\lib
 */
class CommonFunction
{

    /**
     * GET请求
     *
     * @param $url
     * @return bool|mixed
     */
    public static function http_get($url)
    {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }

    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @return string content
     */
    public static function http_post($url, $param)
    {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
        }
        if (is_string($param)) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach ($param as $key => $val) {
                $aPOST[] = $key . "=" . urlencode($val);
            }
            $strPOST = join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }

    /*
     * 随机获取字符串
     */
    public static function getRandChar($length, $is_str = true)
    {
        $str = null;
        $strPol = "0123456789";
        if($is_str)
            $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $str;
    }

    /**
     * 客户端IP
     *
     * @param int $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param bool $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    public static function getClientIP($type = 0,$adv=false) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($adv){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }

    /**
     * 加密数据
     *
     * @param string $data 加密数据
     * @param string $key 加密因子
     * @return string
     */
    public static function encrypt($data, $key = '')
    {
        !$key && $key = CommonVar::$encrypt;
        if(is_numeric($data)){
            $data = strval($data);
        }
        $key = md5($key);
        $x = 0;
        $len = strlen($data);
        $l = strlen($key);
        $char = '';
        $str = '';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= $key{$x};
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
        }
        return base64_encode($str);
    }

    /**
     * 解密数据
     *
     * @param string $data 加密数据
     * @param string $key 加密因子
     * @return string
     */
    public static function decrypt($data, $key = '')
    {
        !$key && $key = CommonVar::$encrypt;
        $key = md5($key);
        $x = 0;
        $data = base64_decode($data);
        $len = strlen($data);
        $l = strlen($key);
        $char = '';
        $str = '';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            } else {
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return $str;
    }

    /**
     * 公用密码加密方法
     * @param string $password 原始密码
     * @return string
     */
    public static function encryptPassword($password){
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * 公用密码验证密方法
     *
     * @param $password
     * @param $hash
     * @return bool
     */
    public static function validatePassword($password, $hash){
        return password_verify($password, $hash);
    }

    /**
     * 格式化秒数
     *
     * @param $seconds
     * @param bool $isShort
     * @return string
     */
    public static function formatSecond($seconds, $isShort = false){
        if ($seconds > 3600){
            $days = intval($seconds/(3600*24));
            $hours = intval($seconds%(3600*24)/3600);
            $minutes = $seconds % 3600;
            $time = '';
            if($days){
                $time = $days."天";
            }
            if($isShort){
                $time .= $hours.":".gmstrftime('%M:%S', $minutes);
            }else{
                $time .= $hours."时".gmstrftime('%M分%S秒', $minutes);
            }
        }else{
            $time = gmstrftime('%H:%M:%S', $seconds);
        }
        return $time;
    }

    /**
     * 查找数组中缺少的值
     *
     * @param $data
     * @return bool|float
     */
    public static function lessNumInArray($data){
        sort($data);
        foreach ($data as $key => $value){
            if($value != 1){
                return 1;
            }
            if(!array_key_exists($key+1, $data)){
                return false;
            }
            if($data[$key+1] - $value > 1){
                return $value+1;
            }
        }
        return false;
    }

    /**
     * 格式化金额
     *
     * @param $number
     * @param bool $fractional
     * @return string
     */
    public static function formatMoney($number, $fractional = true){
        $number = intval(sprintf('%.0f', $number)) / 100;
        if($fractional)
            return number_format($number, 2);
        return number_format($number);
    }

    /*
     * 处理身份证号显示
     */
    public static function dealIdentity($identity){
        return strlen($identity) == 15 ? substr_replace($identity,"********",4,8) : substr_replace($identity,"********",6,8);
    }

    /*
     * 处理七牛账号显示
     */
    public static function dealQiniuAccount($value){
        return substr($value, 0, 8).'********'.substr($value, -8);
    }

    /**
     * 格式化时间
     *
     * @param $value
     * @return string
     */
    public static function dateTime($value){
        if(!$value) return null;
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * 格式化尺寸
     *
     * @param $value
     * @return string
     */
    public static function formatSize($value){
        if($value > 1024*1024*1024){
            return number_format($value/(1024*1024*1024), 2).'GB';
        }else if($value > 1024*1024){
            return number_format($value/(1024*1024), 2).'MB';
        }else{
            return number_format($value/1024, 2).'KB';
        }
    }

    /**
     * 判断是否图片
     *
     * @param $mime
     * @return bool
     */
    public static function isImage($mime){
        $imageMimes = [
            'image/bmp',
            'image/cis-cod',
            'image/gif',
            'image/ief',
            'image/jpeg',
            'image/jpeg',
            'image/jpeg',
            'image/pipeg',
            'image/svg+xml',
            'image/tiff',
            'image/tiff',
            'image/x-cmu-raster',
            'image/x-cmx',
            'image/x-icon',
            'image/x-portable-anymap',
            'image/x-portable-bitmap',
            'image/x-portable-graymap',
            'image/x-portable-pixmap',
            'image/x-rgb',
            'image/x-xbitmap',
            'image/x-xpixmap',
            'image/x-xwindowdump'
        ];
        return in_array($mime, $imageMimes);
    }

    /**
     * 生成链接
     *
     * @param $text
     * @param $url
     * @param $class
     * @param $event
     * @param string $full
     * @param string $height
     * @param string $refresh
     * @return string
     */
    public static function createLink($text, $url, $class, $event, $full="false", $height='', $refresh="true"){
        $options = ['class'=>$class, 'lay-event'=>$event,  'data-full'=>$full, ''=>"", 'data-refresh'=>$refresh];
        if($event == 'download'){
            $options['data-url'] = $url;
            $options['target'] = '_blank';
        }else{
            $options['data-url'] = $url;
        }
        if($height){
            $options['data-height'] = $height;
        }
        return Html::a($text, null, $options);
    }

}
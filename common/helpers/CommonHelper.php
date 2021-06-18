<?php

namespace common\helpers;

use yii;

class CommonHelper
{
    /**
     * curl的Get请求
     * @param $url
     * @return bool|string
     */
    public static function curlGet($url,$https = false)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if($https){
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
        }else{
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    /**
     * curl的POST请求
     * @param $url
     * @param $data
     * @return bool|string
     */
    public static function curlPost($url, $data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    /**
     * curl的POST请求，以json格式发送
     * @param $url
     * @param $data
     * @return bool|string
     */
    public static function curlPostJson($url, $data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
        ]);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    /**
     * 溢出省略
     * $str为要进行截取的字符串，$length为截取长度
     * 汉字算一个字，字母算半个字
     */
    public static function subtext($text, $length)
    {
        if (mb_strlen($text, 'utf8') > $length) {
            return mb_substr($text, 0, $length, 'utf8') . '...';
        } else {
            return $text;
        }

    }

    /**
     * 判断文件夹是否为空
     * @param $dir
     * @return bool
     * @author caozongchao
     */
    public static function isDirectoryEmpty($dir)
    {
        $handle = opendir($dir);
        while (false !== ($entry = readdir($handle))) {
            if ($entry != '.' && $entry != '..') {
                return false;
            }
        }
        return true;
    }

    /**
     * 格式化容量大小
     * @param $size
     * @return string
     */
    public static function formatSize($size)
    {
        if ($size >= 1073741824) {
            $size = round($size / 1073741824 * 100) / 100 . ' GB';
        } elseif ($size >= 1048576) {
            $size = round($size / 1048576 * 100) / 100 . ' MB';
        } elseif ($size >= 1024) {
            $size = round($size / 1024 * 100) / 100 . ' KB';
        } else {
            $size = $size . ' Bytes';
        }
        return $size;
    }

    /**
     * 获取视频大小，单位Mb
     * @param $size
     * @return string
     */
    public static function formatSizeMb($size)
    {
        return round($size / 1048576 * 100) / 100;
    }

    /**
     * @param $width
     * @param $height
     * @return string
     */
    public static function dimensionRatio($width, $height)
    {
        $dimensionRatio16 = abs(9 * ($width / 16) - $height);
        $dimensionRatio4 = abs(3 * ($width / 4) - $height);
        if ($dimensionRatio4 < $dimensionRatio16) {
            return '4:3';
        }
        return '16:9';
    }

    /**
     * 获取数组的维度
     * @param $array
     * @return int
     */
    public static function getArrayDim($array)
    {
        if (!is_array($array)) return 0;
        else {
            $dim = 0;
            foreach ($array as $item) {
                $t1 = self::getArrayDim($item);
                if ($t1 > $dim) $dim = $t1;
            }
            return $dim + 1;
        }
    }

    /**
     * 多个连续空格只保留一个
     *
     * @param string $string 待转换的字符串
     * @return unknown
     */
    static public function mergeSpace($string)
    {
        return preg_replace("/\s(?=\s)/", "\\1", $string);
    }

    /**
     * 将秒转换为是分秒格式
     * @param $seconds
     * @return string
     */
    static function formatSeconds($seconds)
    {
        if ($seconds > 3600) {
            $hours = intval($seconds / 3600);
            $time = $hours . ":" . gmstrftime('%M:%S', $seconds);
        } else {
            $time = gmstrftime('%H:%M:%S', $seconds);
        }
        return $time;
    }

    /**
     * 多维数组中取某列值
     * @param $input
     * @param $columnKey
     * @param $result
     */
    static function arrayColumn($input, $columnKey, &$result)
    {
        foreach ($input as $key => $value) {
            if (is_array($value)) {
                self::arrayColumn($value, $columnKey, $result);
            } else {
                if ($key == $columnKey) {
                    $result[] = $value;
                }
            }
        }
    }

    /**
     * 格式化秒自动到 分或者时
     * @param int $second
     * @return string
     */
    public static function formatSecond($second = 0)
    {
        $result = '00:00:00';
        if ($second > 0) {
            $hour = floor($second / 3600);
            if ($hour < 10) {
                $hour = '0' . $hour;
            }
            $minute = floor(($second - 3600 * $hour) / 60);
            if ($minute < 10) {
                $minute = '0' . $minute;
            }
            $second = floor((($second - 3600 * $hour) - 60 * $minute) % 60);
            if ($second < 10) {
                $second = '0' . $second;
            }
            $result = $hour . ':' . $minute . ':' . $second;
        }
        return $result;
    }

    /**
     * 下载excel
     * @param $filePath
     */
    public static function downloadExcel($filePath)
    {
        set_time_limit(0);
        $fileSize = filesize($filePath);
        $fileName = pathinfo($filePath, PATHINFO_BASENAME);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //07
        // header('Content-Type: application/vnd.ms-excel'); //03
        header('Content-Length:' . $fileSize);
        header('Content-Disposition:attachment;filename=' . $fileName);
        $fp = fopen($filePath, "rb");
        fseek($fp, 0);
        while (!feof($fp)) {
            print(fread($fp, 1024 * 8));
            flush();
            ob_flush();
        }
        fclose($fp);
        exit();
    }

    /**
     * 下载文件
     * @param $filePath
     */
    public static function downloadFile($filePath)
    {
        set_time_limit(0);
        $fileSize = filesize($filePath);
        $fileName = pathinfo($filePath, PATHINFO_BASENAME);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$fileName);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: '.$fileSize);

        $fp = fopen($filePath, "rb");
        fseek($fp, 0);
        while (!feof($fp)) {
            print(fread($fp, 1024 * 8));
            flush();
            ob_flush();
        }
        fclose($fp);
        exit();
    }

    /**
     *  * 验证手机的有效性
     *  * @param       string      data
     *  * @return      bool
     *  */
    public static function isMobile($data)
    {
        $reg = "/^1[3|4|5|6|7|8|9][0-9]\d{8}$/";
        return preg_match($reg, $data);
    }


    /**
     *  * 验证身份证的有效性
     *  * @param       string      data
     *  * @return      bool
     *  */
    public static function isIdcard($data)
    {
        $reg = "/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/";
        return preg_match($reg, $data);
    }

    /**
     * 验证姓名
     * @param $data
     * @return false|int
     */
    public static function isRealname($data)
    {
        $reg = "/^[\u4e00-\u9fa5]|[a-zA-Z]$/";
        return preg_match($reg, $data);
    }


    /**
     *  * 验证邮箱的有效性
     *  * @param       string      data
     *  * @return      bool
     *  */
    public static function isEmail($data)
    {
        $reg = "/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/";
        return preg_match($reg, $data);
    }

    /**
     * @param int $key
     * @return string
     */
    public static function getCapital($key = 0)
    {
        $firstId = 65;
        return strtoupper(chr($firstId + $key));
    }

    /**
     * 获取时间戳
     * 早中晚
     */
    public static function getTimeSlot($slot = 0)
    {
        switch ($slot) {
            case 0:
                $start_time = strtotime(date('Y-m-d 09:00:00'));
                $end_time = strtotime(date('Y-m-d 11:00:00'));
                break;
            case 1:
                $start_time = strtotime(date('Y-m-d 14:00:00'));
                $end_time = strtotime(date('Y-m-d 17:00:00'));
                break;
            case 2:
                $start_time = strtotime(date('Y-m-d 19:00:00'));
                $end_time = strtotime(date('Y-m-d 21:00:00'));
                break;
            default:
                $start_time = strtotime(date('Y-m-d 14:00:00'));
                $end_time = strtotime(date('Y-m-d 17:00:00'));
                break;
        }
        return (rand($start_time, $end_time)) - 3600;
    }


    public static function getRandLottery($remainCount)
    {

        if ($remainCount > 0) {
            $arr = array(
                array('id' => 1, 'name' => '特等奖', 'v' => 95),
                array('id' => 2, 'name' => '没中奖', 'v' => 5)
            );
        } else {
            $arr = array(
                array('id' => 1, 'name' => '特等奖', 'v' => 5),
                array('id' => 2, 'name' => '没中奖', 'v' => 95)
            );
        }
        $res = LotteryHelper::get_rand($arr);
        $name = $res['name'];
        $is_lottery = 0;
        if ($name != '没中奖') {
            $is_lottery = 1;
        }
        return $is_lottery;
    }

    public static function getRandFloat($min = 0, $max = 1)
    {
        $num = $min + mt_rand() / mt_getrandmax() * ($max - $min);
        return round(sprintf("%.2f", $num));
    }

    /**
     *
     * @param $what 1 数字  2  字母  3 混合
     * @param $number
     * @return string
     */
    public static function genRandom($what, $number)
    {
        $string = '';
        for ($i = 1; $i <= $number; $i++) {
            //混合
            $panduan = 1;
            if ($what == 3) {
                if (rand(1, 2) == 1) {
                    $what = 1;
                } else {
                    $what = 2;
                }
                $panduan = 2;
            }
            //数字
            if ($what == 1) {
                $string .= rand(0, 9);
            } elseif ($what == 2) {
                //字母
                $rand = rand(0, 24);
                $b = 'a';
                for ($a = 0; $a <= $rand; $a++) {
                    $b++;
                }
                $string .= $b;
            }
            if ($panduan == 2)
                $what = 3;
        }
        return $string;
    }

    /**
     * @param int $code
     * @param int $status
     * @param string $msg
     * @param array $data
     * @param bool $isDebug
     * @return array
     */
    public static function formatJson($code = 101, $status = 100001, $msg = '未知', $data = [],$ext=[],$isDebug = false)
    {
        $res = [
            'code' => $code,
            'status' => $status,
            'message' => $msg,
            'type' => 'yii\\web\\SuccessRequest',
            'data' => $data,
            'ext'=>$ext
        ];
        if ($isDebug == true) {
            var_export($res);
            exit;
        }
        return $res;
    }

    /**
     * 视频时长截取2位小数
     */
    public static function cutDecimal($time = 0, $position = 2)
    {
        //计算长度
        $temp_position = pow(10, $position);
        $time = $time * $temp_position;
        $time = intval($time) / $temp_position;
        return $time;
    }

    public static function utcTime($seconds)
    {
        date_default_timezone_set('UTC');
        $timestamp = new \DateTime(date('Y-m-d H:i:s', time() + $seconds));
        $timeStr = $timestamp->format("Y-m-d\TH:i:s\Z");
        return $timeStr;
    }

    /**
     * @return bool
     */
    public static  function isPc(){
        $useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $useragent_commentsblock=preg_match('|.∗?|',$useragent,$matches)>0?$matches[0]:'';
        $mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
        $mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');
        $found_mobile=self::CheckSubstrs($mobile_os_list,$useragent_commentsblock) || self::CheckSubstrs($mobile_token_list,$useragent);
        if ($found_mobile){
            return false;
        }else{
            return true;
        }
    }

    /**
     * @return bool
     */
    public static function is_mobile()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
        $is_mobile = false;
        foreach ($mobile_agents as $device) {//这里把值遍历一遍，用于查找是否有上述字符串出现过
            if (stristr($user_agent, $device)) { //stristr 查找访客端信息是否在上述数组中，不存在即为PC端。
                $is_mobile = true;
                break;
            }
        }
        return $is_mobile;
    }


    /**
     * @param $substrs
     * @param $text
     * @return bool
     */
    public static function CheckSubstrs($substrs,$text){
        foreach($substrs as $substr){
            if(false!==strpos($text,$substr)){
                return true;
            }else{
                return false;
            }
         }
    }
    /*
     * 随机颜色
     * @return string
     */
    public static function randColor(){
        mt_srand((double)microtime()*1000000);
        $c = '';
        while(strlen($c)<6){
            $c .= sprintf("%02X", mt_rand(0, 255));
        }
        return $c;
    }
    /**
     * token 值
     * @param $secret_key
     * @return string
     */
    public static function tokenVal($client_id, $client_secret, $product_id) {
        return md5(\Yii::$app->params['private_key'] . '-' . $client_id . '-' . $client_secret . '-' . $product_id . '-' . time() . uniqid());
    }

    /**
     * 返回json数据
     * @param int $code
     * @param int $status
     * @param string $msg
     * @param array $data
     * @param bool $isDebug
     * @return false|string
     */
    public static function formatJsonRes($code = 101, $status = 100001, $msg = '未知', $data = [],$ext=[] ,$isDebug = false) {
        $res = [
            'code' => $code,
            'status' => $status,
            'message' => $msg,
            'type' => 'yii\\web\\SuccessRequest',
            'data' => $data,
            'ext'=>$ext
        ];
        if ($isDebug == true) {
            var_export($res);
            exit;
        }
        return json_encode($res);
    }
    /**
     * @return string
     * 生成交易
     */
    public  static  function genSn()
    {
        $rand = self::genRandom(1, 6);
        $order_sn = date('YmdHis') . $rand;
        return $order_sn;
    }

    /**
     * 中文验证
     * @param $data
     * @return false|int
     */
    public static function isChinese($data)
    {
        $reg = "/^[\x{4e00}-\x{9fa5}]+$/u";
        return preg_match($reg, $data);
    }

    /**
     * 英文验证
     * @param $data
     * @return false|int
     */
    public static function isEnglish($data)
    {
        $reg = "/^A-Za-z]+$/u";
        return preg_match($reg, $data);
    }

    /**
     * 中英文验证
     * @param $data
     * @return false|int
     */
    public static function isMulty($data)
    {
        $reg = "/^[\x{4e00}-\x{9fa5}A-Za-z]+$/u";
        return preg_match($reg, $data);
    }

    /**
     * 获取真实的ip
     * @return bool|mixed|string
     */
    public  static function getRealIp()
    {
        $ip=false;
        //客户端IP 或 NONE
        if(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        //客户端IP 或 (最后一个)代理服务器 IP
        return ($ip ? $ip :(isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:false));
    }
}

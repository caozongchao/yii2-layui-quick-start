<?php

namespace common\helpers;

use yii;
use common\helpers\CommonHelper;

define('FFMPEG_GET_CMD', ' -i "%s" 2>&1');

class FFmpegHelper
{
    private static $ffmpeg;

    private static $ffprobe;

    private $videoCode = 'libfdk_aac';

    private static $ffmpegCmdPrefix;

    public function __construct()
    {
        self::$ffmpeg = \FFMpeg\FFMpeg::create(Yii::$app->params['ffmpeg.config']);
        self::$ffprobe = \FFMpeg\FFProbe::create(Yii::$app->params['ffmpeg.config']);
        self::$ffmpegCmdPrefix = 'ffmpeg';
    }

    /**
     * 获取当前视频编码 [X264 编码]
     * @return \FFMpeg\Format\Video\X264
     */
    private function getVideoCode()
    {
        return new \FFMpeg\Format\Video\X264($this->videoCode);
    }

    /**
     * 获取时间点视频时间格式
     * @param $point
     * @return \FFMpeg\Coordinate\TimeCode
     */
    private function getVideoTimeCode($point)
    {
        return \FFMpeg\Coordinate\TimeCode::fromSeconds($point);
    }

    /**
     * 获取视频时长
     * @param $videoPath
     * @return mixed
     */
    public function getVideoDuration($videoPath)
    {
        return self::$ffprobe->format($videoPath)->get('duration');
    }

    /**
     * 获取视频编码
     * @param $videoPath
     * @return mixed
     */
    public function getVideoCodeName($videoPath)
    {
        return self::$ffprobe->streams($videoPath)->videos()->first()->get('codec_name');
    }

    /**
     * 提取位置上的视频图像
     * @param $videoPath
     * @param $videoImagePath
     * @param int $point 提取位置 秒
     */
    public function videoImage($videoPath,$videoImagePath,$point = 2)
    {
        $video = self::$ffmpeg->open($videoPath);
        $frame = $video->frame($this->getVideoTimeCode($point));//提取第几秒的图像
        $frame->save($videoImagePath);
    }

    /**
     * 提取视频 gif 图
     * @param $videoPath
     * @param $gitImagePath
     * @param int $start
     * @param int $width
     * @param int $height
     * @param int $duration
     */
    public function videoGifImage($videoPath,$gitImagePath,$start = 10,$width = 400,$height = 200, $duration = 3)
    {
        $video = self::$ffmpeg->open($videoPath);
        $video->gif($this->getVideoTimeCode($start), new \FFMpeg\Coordinate\Dimension($width, $height), $duration)->save($gitImagePath);
    }

    /**
     * 合并视频
     * @param $videoPath
     * @param $concatPath
     * @param array $slicePath 分片视频列表 array($v1,$v2,$v3)
     */
    public function videoConcat($videoPath,$concatPath,$slicePath = array())
    {
        $video = self::$ffmpeg->open($videoPath);
        $video->concat($slicePath)->saveFromSameCodecs($concatPath, TRUE);
    }

    /**
     * 视频加水印图片
     * @param $videoPath
     * @param int $bottom
     * @param int $right
     * @param $image
     * @param $waterMarkPath
     * @param $position
     */
    public function videoWaterMark($videoPath,$image,$waterMarkPath,$position = 'relative',$bottom = 50,$right = 50)
    {
        $video = self::$ffmpeg->open($videoPath);
        $video->filters()->watermark($image, array(
            'position' => $position,
            'bottom' => $bottom,
            'right' => $right
        ));
        $video->save($this->getVideoCode(), $waterMarkPath);
    }

    /**
     * 验证
     * @param $videoInfo
     * @return bool
     */
    public function checkVideoInfo($videoInfo)
    {
        $videoInfo = $this->getFileInfo($videoInfo['path']);
        if (!$videoInfo || !isset($videoInfo[0])) {
            return false;
        }
        $videoInfo = $videoInfo[0];
        $originBitRate = $videoInfo['bitrate'];
        $resolvingWidth = $videoInfo['width'];
        $resolvingHeight = $videoInfo['height'];

        if ($originBitRate < $videoInfo['bitRate']) {
            return false;
        }
        if ($resolvingWidth < $videoInfo['resolving_width'] || $resolvingHeight < $videoInfo['resolving_height']) {
            return false;
        }
        return true;
    }

    /**
     * 视频转换格式
     * @param $videoPath
     * @param $transPath
     * @param $kiloBit
     * @param $unique_key
     * @param $width
     * @param $height
     * @return bool|\FFMpeg\Media\Audio|\FFMpeg\Media\Video
     */
    public function videoTransType($videoPath,$transPath,$kiloBit,$unique_key,$width,$height)
    {
        try{
            $video = self::$ffmpeg->open($videoPath);
            $format = $this->getVideoCode();

            $format->on('progress', function ($video, $format, $percentage) use($unique_key){
                if (!\Yii::$app->cache->exists($unique_key)) {
                    $video->fs->clean($video->fsId);exit;
                }else{
                    \Yii::$app->cache->set($unique_key,$percentage,\Yii::$app->params['trans_coding_expire']);
                }
            });

            $video->filters()
                ->resize(new \FFMpeg\Coordinate\Dimension($width, $height),\FFMpeg\Filters\Video\ResizeFilter::RESIZEMODE_INSET, true)
                ->synchronize();

            $format->setKiloBitrate($kiloBit);
            $res = $video->save($format, $transPath,$unique_key);
        }catch (\Exception $e) {
            \Yii::info('视频转换格式失败: '.var_export($e->getMessage(),true));
            return false;
        }
        return $res;
    }

    /**
     * 视频分割
     * @param $videoPath
     * @param $clipPath
     * @param $start
     * @param $duration
     * @return bool|\FFMpeg\Media\Video
     */
    public function videoClip($videoPath,$clipPath,$start,$duration)
    {
        try{
            $video = self::$ffmpeg->open($videoPath);
            $clip = $video->clip($this->getVideoTimeCode($start), $this->getVideoTimeCode($duration));
            $res = $clip->save($this->getVideoCode(), $clipPath);
        }catch (\Exception $e) {
            \Yii::info('视频分割失败: '.var_export($e->getMessage(),true));
            return false;
        }
        return $res;
    }

    /**
     * 调整视频分辨率
     * @param $videoPath
     * @param $resizePath
     * @param int $width
     * @param int $height
     * @return bool|\FFMpeg\Media\Audio|\FFMpeg\Media\Video
     */
    public function videoResize($videoPath,$resizePath,$width = 320,$height = 240)
    {
        try{
            $video = self::$ffmpeg->open($videoPath);
            $video->filters()
                ->resize(new \FFMpeg\Coordinate\Dimension($width, $height))
                ->synchronize();
            $res = $video->save($this->getVideoCode(), $resizePath);
        }catch (\Exception $e) {
            \Yii::info('视频分辨率调整失败: '.var_export($e->getMessage(),true));
            return false;
        }
        return $res;
    }

    /**
     * 获取视频信息
     * @param $filename
     * @return array|mixed
     */
    public function getFileInfo($filename)
    {
        ob_start();
        passthru(sprintf(self::$ffmpegCmdPrefix.FFMPEG_GET_CMD, $filename));
        $videoInfo = ob_get_contents();
        ob_end_clean();
        $returnData = array();
        $array_ = explode("\n", $videoInfo);
        foreach ($array_ as $oneLine) {
            if(strstr($oneLine,'Duration:')){
                preg_match("/Duration: (.*?), start: (.*?), bitrate: (\d*) kb\/s/", $oneLine, $match);
                $returnData['duration'] = $match[1]; //播放时间
                $arrDuration = explode(':', $match[1]);
                $returnData['seconds'] = $arrDuration[0] * 3600 + $arrDuration[1] * 60 + $arrDuration[2]; //转换播放时间为秒数
                $returnData['start'] = $match[2]; //开始时间
                $returnData['bitrate'] = $match[3]; //码率(kb)
            }
            if(strstr($oneLine,'Video:')){
                //去掉括号，因为里面可能会包含逗号照成后面正则匹配错误
                $oneLine = preg_replace('/\(([^\)]+)\)/','',$oneLine);
                preg_match("/Video: (.*?), (.*?),(.*?),(.*?), (.*?)[,\s]/", $oneLine, $match);
                $returnData['vcodec'] = $match[1]; //视频编码格式
                $returnData['vformat'] = $match[2]; //视频格式
                $arrResolution = explode('x', $match[3]);
                $returnData['width'] = intval($arrResolution[0]);
                $returnData['height'] = intval($arrResolution[1]);
                $returnData['fps'] = $match[5];
                $returnData['resolution'] = $returnData['width'].'*'.$returnData['height']; //分辨率
            }
            if(strstr($oneLine,'Audio:')){
                //去掉括号，因为里面可能会包含逗号照成后面正则匹配错误
                $oneLine = preg_replace('/\(([^\)]+)\)/','',$oneLine);
                //preg_match("/Audio: (\w*), (\d*) Hz/", $oneLine, $match);
                preg_match("/Audio: (.*), (\d*) Hz/", $oneLine, $match);
                $returnData['acodec'] = $match[1]; //音频编码
                $returnData['asamplerate'] = $match[2]; //音频采样频率
            }
        }
        $returnData['size'] = CommonHelper::formatSizeMb(filesize($filename)); //文件大小

        $returnData['dimensionRatio'] = CommonHelper::dimensionRatio($returnData['width'],$returnData['height']);
        return $returnData;
    }

    /**
     * m3u8
     */
    public function m3u8()
    {
        //先将视频转换成视频ts文件
        $str="ffmpeg -y -i video/1.mp4 -vcodec copy -acodec copy -vbsf h264_mp4toannexb video3/output.ts";
        system($str,$res);
        echo $res;

        //将ts视频文件分割成视频流文件ts，并生成索引文件m3u8
        $str="ffmpeg -i video3/output.ts  -c copy -map 0 -f segment -segment_list video3/index.m3u8 -segment_time 10 video3/video-%03d.ts";
        system($str,$res);
        echo $res;
    }
}

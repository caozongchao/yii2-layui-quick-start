<?php

namespace common\services;

use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use common\helpers\CommonHelper;
use common\models\Live;

class LiveService
{
    /**
     * 新增其他类型永久素材
     * @param string $mediaPath
     * @param string $type
     * @param array $data 视频素材需要description
     * @return bool|mixed
     * @throws \yii\web\HttpException
     */
    public function addMaterial($mediaPath,$accessToken,$type = 'image')
    {
        $url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=$accessToken&type=$type";
        $data = [
            'media' => $mediaPath
        ];
        $res = CommonHelper::curlPost($url,$data);
        Yii::error('=======新增其他类型永久素材'.$res);
        $resArray = json_decode($res,true);
        if(isset($res['errcode'])){
            throw new \Exception('上传素材异常');
        }else{
            return $res['media_id'];
        }
    }

    public static function createRoom($accessToken,$uwid)
    {
        $params = [];
        $params['name'] = Yii::$app->request->post('name');

        $savePath = Yii::getAlias('@app/web/uploads/'.date('Ymd'));
        $uploadFile = new UploadedFile();
        $file = $uploadFile->getInstanceByName('coverImg');
        FileHelper::createDirectory($savePath,0777);
        $time = date('YmdHis');
        $finalName = $savePath.DIRECTORY_SEPARATOR.$time.'.'.$file->extension;
        $file->saveAs($finalName,true);
        $coverImg = self::addMaterial($finalName,$accessToken);
        $params['coverImg'] = $coverImg;

        $params['startTime'] = Yii::$app->request->post('startTime');
        $params['endTime'] = Yii::$app->request->post('endTime');
        $params['anchorName'] = Yii::$app->request->post('anchorName');
        $params['anchorWechat'] = Yii::$app->request->post('anchorWechat');
        $params['subAnchorWechat'] = Yii::$app->request->post('subAnchorWechat','');
        $params['createrWechat'] = Yii::$app->request->post('createrWechat','');

        $savePath = Yii::getAlias('@app/web/uploads/'.date('Ymd'));
        $uploadFile = new UploadedFile();
        $file = $uploadFile->getInstanceByName('shareImg');
        FileHelper::createDirectory($savePath,0777);
        $time = date('YmdHis');
        $finalName = $savePath.DIRECTORY_SEPARATOR.$time.'.'.$file->extension;
        $file->saveAs($finalName,true);
        $shareImg = self::addMaterial($finalName,$accessToken);
        $params['shareImg'] = $shareImg;

        $savePath = Yii::getAlias('@app/web/uploads/'.date('Ymd'));
        $uploadFile = new UploadedFile();
        $file = $uploadFile->getInstanceByName('feedsImg');
        if($file){
            FileHelper::createDirectory($savePath,0777);
            $time = date('YmdHis');
            $finalName = $savePath.DIRECTORY_SEPARATOR.$time.'.'.$file->extension;
            $file->saveAs($finalName,true);
            $feedsImg = self::addMaterial($finalName,$accessToken);
            $params['feedsImg'] = $feedsImg;
        }

        $params['isFeedsPublic'] = Yii::$app->request->post('isFeedsPublic',1);
        $params['type'] = Yii::$app->request->post('type');
        $params['closeLike'] = Yii::$app->request->post('closeLike',0);
        $params['closeGoods'] = Yii::$app->request->post('closeGoods',0);
        $params['closeComment'] = Yii::$app->request->post('closeComment',0);
        $params['closeReplay'] = Yii::$app->request->post('closeReplay',1);
        $params['closeShare'] = Yii::$app->request->post('closeShare',0);
        $params['closeKf'] = Yii::$app->request->post('closeKf',1);

        $url = 'https://api.weixin.qq.com/wxaapi/broadcast/room/create?access_token='.$accessToken;
        $res = CommonHelper::curlPostJson($url,$params);
        Yii::error('=======创建直播间'.$res);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            $live = new Live();
            $live->uw_id = $uwid;
            $live->room_id = $res['roomId'];
            $live->qrcode_url = $res['qrcode_url'];
            $live->save();
            return true;
        }else{
            return false;
        }
    }

    public static function getRoomList($accessToken,$start = 0,$limit = 10)
    {
        $params = ['start' => $start,'limit' => $limit];
        $url = 'https://api.weixin.qq.com/wxa/business/getliveinfo?access_token='.$accessToken;
        $res = CommonHelper::curlPostJson($url,$params);
        Yii::error('=======获取直播间'.$res);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return $res['room_info'];
        }else{
            return false;
        }
    }

    public static function getReplay($accessToken,$roomId = null,$start = 0,$limit = 10)
    {
        $params = ['action' => 'get_replay','room_id' => $roomId,'start' => $start,'limit' => $limit];
        $url = 'https://api.weixin.qq.com/wxa/business/getliveinfo?access_token='.$accessToken;
        $res = CommonHelper::curlPostJson($url,$params);
        Yii::error('=======获取直播回放'.$res);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return $res['live_replay'];
        }else{
            return false;
        }
    }

    public static function addGoods($accessToken,$roomId = null,$goodsIds = [])
    {
        $params = ['roomId' => $roomId,'ids' => $goodsIds];
        $url = 'https://api.weixin.qq.com/wxaapi/broadcast/room/addgoods?access_token='.$accessToken;
        $res = CommonHelper::curlPostJson($url,$params);
        Yii::error('=======导入商品'.$res);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return true;
        }else{
            return false;
        }
    }

    public static function deleteRoom($accessToken,$roomId)
    {
        $params = ['id' => $roomId];
        $url = 'https://api.weixin.qq.com/wxaapi/broadcast/room/deleteroom?access_token='.$accessToken;
        $res = CommonHelper::curlPostJson($url,$params);
        Yii::error('=======删除直播间'.$res);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return true;
        }else{
            return false;
        }
    }

    public static function getPushUrl($accessToken,$roomId)
    {
        $params = ['roomId' => $roomId];
        $url = 'https://api.weixin.qq.com/wxaapi/broadcast/room/getpushurl?access_token='.$accessToken;
        $res = CommonHelper::curlPostJson($url,$params);
        Yii::error('=======获取直播推流地址'.$res);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return $res['pushAddr'];
        }else{
            return false;
        }
    }
}
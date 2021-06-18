<?php

namespace common\services;

use Yii;
use common\models\Videos;
use common\models\UwFans;
use common\models\UwCollectVideos;
use common\models\UwLikeVideos;
use common\models\UserWechat;
use yii\helpers\ArrayHelper;

class VideosService
{
    public static function getOneById($id)
    {
        $model = Videos::findOne($id);
        if($model){
            if($model->status == 0){
                throw new \Exception('视频被下架');
            }
            return $model;
        }else{
            return false;
        }
    }

    /**
     * 随机推荐
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getRecommend($uwid,$first)
    {
        $datas = [];
        if($first){
            $datas = Videos::find()->where(['<>','top',0])->andWhere(['status' => 1])->orderBy(['top' => SORT_DESC])->asArray()->all();
        }
        if(!$datas){
            $table = Videos::tableName();
            $max = Videos::find()->max('id');
            $min = Videos::find()->min('id');
            $datas = Yii::$app->db->createCommand("SELECT * FROM $table WHERE status=1 and id >= (floor((:max - :min) * RAND()) + :min) LIMIT 10")
                ->bindValues([':max' => $max,':min' => $min])
                ->queryAll();
        }
        if($datas){
            foreach ($datas as $key => &$value) {
                $uw = UserWechatService::getOneById($value['uw_id']);
                $value['uw_nickname'] = $uw->nickname;
                $value['uw_avatar'] = strstr($uw->avatar,'http') ? $uw->avatar : Yii::$app->params['apiHost'].$uw->avatar;
                $mall = MallService::get($value['uw_id'],false);
                if($mall){
                    $value['mall'] = $mall;
                }else{
                    $value['mall'] = '';
                }
                if($uwid){
                    //检测点赞
                    $checkLike = UwLikeVideos::find()->where(['uw_id' => $uwid,'video_id' => $value['id']])->exists();
                    if($checkLike){
                        $value['is_like'] = 1;
                    }else{
                        $value['is_like'] = 0;
                    }
                    //检测关注
                    $checkFollow = UwFans::find()->where(['uw_id' => $value['uw_id'],'fan_id' => $uwid])->exists();
                    if($checkFollow){
                        $value['is_follow'] = 1;
                    }else{
                        $value['is_follow'] = 0;
                    }
                    //检测是不是自己
                    if($uwid == $value['uw_id']){
                        $value['is_self'] = 1;
                    }else{
                        $value['is_self'] = 0;
                    }
                }
                $value['allow_comments'] = 0;
                if(Yii::$app->globalSetting->get('allow_comments') == 'YES'){
                    $value['allow_comments'] = 1;
                }
            }
        }
        return $datas;
    }

    /**
     * 关注人最新视频
     * @param $params
     * @return array
     * @throws \Exception
     */
    public static function getFollowsVideosList($params)
    {
        $query = UwFans::find()->where(['fan_id' => $params['uwid']]);
        $followIds = $query->select(['uw_id'])->column();
        //没有关注
        if(!$followIds){
            throw new \Exception('尚未关注用户');
        }
        $start = ($params['page'] - 1) * $params['per'];
        $q = clone $query;
        $count = $q->count();
        $data = Videos::find()->where(['in','uw_id',$followIds])->andWhere(['status' => 1])->orderBy(['id' => SORT_DESC])->limit($params['per'])->offset($start)->asArray()->all();
        if($data){
            foreach($data as $key => &$value) {
                $uw = UserWechatService::getOneById($value['uw_id']);
                $value['uw_nickname'] = $uw->nickname;
                $value['uw_avatar'] = strstr($uw->avatar,'http') ? $uw->avatar : Yii::$app->params['apiHost'].$uw->avatar;
                //检测点赞
                $checkLike = UwLikeVideos::find()->where(['uw_id' => $params['uwid'],'video_id' => $value['id']])->exists();
                if($checkLike){
                    $value['is_like'] = 1;
                }else{
                    $value['is_like'] = 0;
                }
                /*//检测收藏
                $checkCollect = UwCollectVideos::find()->where(['uw_id' => $params['uwid'],'video_id' => $value['id']])->exists();
                if($checkCollect){
                    $value['is_collect'] = 1;
                }else{
                    $value['is_collect'] = 0;
                }*/
                //检测关注
                $checkFollow = UwFans::find()->where(['uw_id' => $value['uw_id'],'fan_id' => $params['uwid']])->exists();
                if($checkFollow){
                    $value['is_follow'] = 1;
                }else{
                    $value['is_follow'] = 0;
                }
                //检测是不是自己
                if($params['uwid'] == $value['uw_id']){
                    $value['is_self'] = 1;
                }else{
                    $value['is_self'] = 0;
                }
            }
        }
        return [
            'count' => $count,
            'data' => $data,
            'pageCount' => ceil($count/$params['per'])
        ];
    }

    /**
     * 点赞视频
     * @param $uwid
     * @param $videoId
     * @return bool
     * @throws \Exception
     */
    public static function likeVideo($uwid,$videoId)
    {
        $video = VideosService::getOneById($videoId);
        $originUw = UserWechat::findOne($video->uw_id);
        $video->like_counts += 1;
        $originUw->receive_like_counts += 1;
        $model = UwLikeVideos::find()->where(['uw_id' => $uwid,'video_id' => $videoId])->one();
        if($model){
            throw new \Exception('已点赞此视频');
        }
        $model = new UwLikeVideos();
        $model->uw_id = $uwid;
        $model->video_id = $videoId;
        if($video->save() && $originUw->save() && $model->save()){
            MsgService::send($uwid,$video->uw_id,'有人点赞了您的视频');
            return true;
        }else{
            throw new \Exception('异常');
        }
    }

    /**
     * 用户喜欢的视频列表
     * @param $params
     * @return array
     */
    public static function getLikeVideosList($params)
    {
        $query = UwLikeVideos::find()->from(UwLikeVideos::tableName().' uwlv')->where(['uwlv.uw_id' => $params['uwid']])->leftJoin(Videos::tableName().' v','uwlv.video_id=v.id')->andWhere(['v.status' => 1]);
        $start = ($params['page'] - 1) * $params['per'];
        $q = clone $query;
        $count = $q->count();
        $videoIds = $query->orderBy(['uwlv.id' => SORT_DESC])->limit($params['per'])->offset($start)->select(['uwlv.video_id'])->column();
        $data = Videos::find()->where(['in','id',$videoIds])->orderBy(['id' => SORT_DESC])->asArray()->all();
        return [
            'count' => $count,
            'data' => $data,
            'pageCount' => ceil($count/$params['per'])
        ];
    }

    /**
     * 取消点赞视频
     * @param $uwid
     * @param $videoId
     * @return bool
     * @throws \Exception
     */
    public static function unlikeVideo($uwid,$videoId)
    {
        $model = UwLikeVideos::find()->where(['uw_id' => $uwid,'video_id' => $videoId])->one();
        if(!$model){
            throw new \Exception('未点赞此视频');
        }
        $video = VideosService::getOneById($videoId);
        $originUw = UserWechat::findOne($video->uw_id);
        $video->like_counts -= 1;
        if($video->like_counts < 0){
            $video->like_counts = 0;
        }
        $originUw->receive_like_counts -= 1;
        if($originUw->receive_like_counts < 0){
            $originUw->receive_like_counts = 0;
        }

        if($video->save() && $originUw->save() && $model->delete()){
            return true;
        }else{
            throw new \Exception('异常');
        }
    }

    /**
     * 用户收藏视频
     * @param $uwid
     * @param $videoId
     * @return bool
     * @throws \Exception
     */
    public static function collectVideo($uwid,$videoId)
    {
        $video = VideosService::getOneById($videoId);
        $originUw = UserWechat::findOne($video->uw_id);
        $video->collect_counts += 1;
        $originUw->receive_collect_counts += 1;
        $model = UwCollectVideos::find()->where(['uw_id' => $uwid,'video_id' => $videoId])->one();
        if($model){
            throw new \Exception('已收藏此视频');
        }
        $model = new UwCollectVideos();
        $model->uw_id = $uwid;
        $model->video_id = $videoId;
        $uw = UserWechatService::getOneById($uwid);
        $uw->collect_counts += 1;
        if($video->save() && $originUw->save() && $model->save() && $uw->save()){
            MsgService::send($uwid,$video->uw_id,'有人收藏了您的视频');
            return true;
        }else{
            throw new \Exception('异常');
        }
    }

    /**
     * 用户收藏的视频列表
     * @param $params
     * @return array
     */
    public static function getCollectVideosList($params)
    {
        $query = UwCollectVideos::find()->from(UwCollectVideos::tableName().' uwcv')->where(['uwcv.uw_id' => $params['uwid']])->leftJoin(Videos::tableName().' v','uwcv.video_id=v.id')->andWhere(['v.status' => 1]);
        $start = ($params['page'] - 1) * $params['per'];
        $q = clone $query;
        $count = $q->count();
        $videoIds = $query->orderBy(['uwcv.id' => SORT_DESC])->limit($params['per'])->offset($start)->select(['uwcv.video_id'])->column();
        $data = Videos::find()->where(['in','id',$videoIds])->orderBy(['id' => SORT_DESC])->asArray()->all();
        if($data){
            foreach ($data as $key => &$value) {
                $uw = UserWechatService::getOneById($value['uw_id']);
                $value['uw_nickname'] = $uw->nickname;
                $value['uw_avatar'] = strstr($uw->avatar,'http') ? $uw->avatar : Yii::$app->params['apiHost'].$uw->avatar;
                //检测点赞
                $checkLike = UwLikeVideos::find()->where(['uw_id' => $params['uwid'],'video_id' => $value['id']])->exists();
                if($checkLike){
                    $value['is_like'] = 1;
                }else{
                    $value['is_like'] = 0;
                }
                //检测关注
                $checkFollow = UwFans::find()->where(['uw_id' => $value['uw_id'],'fan_id' => $params['uwid']])->exists();
                if($checkFollow){
                    $value['is_follow'] = 1;
                }else{
                    $value['is_follow'] = 0;
                }
                //检测是不是自己
                if($params['uwid'] == $value['uw_id']){
                    $value['is_self'] = 1;
                }else{
                    $value['is_self'] = 0;
                }
            }
        }
        return [
            'count' => $count,
            'data' => $data,
            'pageCount' => ceil($count/$params['per'])
        ];
    }

    /**
     * 用户取消收藏视频
     * @param $uwid
     * @param $videoId
     * @return bool
     * @throws \Exception
     */
    public static function uncollectVideo($uwid,$videoId)
    {
        $model = UwCollectVideos::find()->where(['uw_id' => $uwid,'video_id' => $videoId])->one();
        if(!$model){
            throw new \Exception('未收藏此视频');
        }
        $video = VideosService::getOneById($videoId);
        $originUw = UserWechat::findOne($video->uw_id);
        $video->collect_counts -= 1;
        if($video->collect_counts < 0){
            $video->collect_counts = 0;
        }
        $originUw->receive_collect_counts -= 1;
        if($originUw->receive_collect_counts < 0){
            $originUw->receive_collect_counts = 0;
        }
        $uw = UserWechatService::getOneById($uwid);
        $uw->collect_counts -= 1;
        if($uw->collect_counts < 0){
            $uw->collect_counts = 0;
        }
        if($video->save() && $originUw->save() && $model->delete() && $uw->save()){
            return true;
        }else{
            throw new \Exception('异常');
        }
    }

    /**
     * 查询某个用户发布的视频列表
     * @param $params
     * @return array
     */
    public static function getVideosList($params)
    {
        if($params['self']){
            $query = Videos::find()->where(['uw_id' => $params['uwid']]);
        }else{
            $query = Videos::find()->where(['uw_id' => $params['uwid'],'status' => 1]);
        }
        $start = ($params['page'] - 1) * $params['per'];
        $q = clone $query;
        $count = $q->count();
        $data = $query->orderBy(['id' => SORT_DESC])->limit($params['per'])->offset($start)->all();
        return [
            'count' => $count,
            'data' => $data,
            'pageCount' => ceil($count/$params['per'])
        ];
    }
}
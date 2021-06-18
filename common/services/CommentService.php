<?php

namespace common\services;

use Yii;
use common\models\Comments;

class CommentService
{
    /**
     * 创建回复
     * @param $uwid
     * @param $videoId
     * @param $comment
     * @param $pid
     * @return bool
     * @throws \Exception
     */
    public static function createComment($uwid,$videoId,$comment,$pid)
    {
        $video = VideosService::getOneById($videoId);
        $video->comments += 1;

        $model = new Comments();
        $model->uw_id = $uwid;
        $model->video_id = $videoId;
        $model->comment = $comment;
        $model->parent_id = $pid;
        if($video->save() && $model->save()){
            return true;
        }else{
            throw new \Exception('异常');
        }
    }

    /**
     * 视频评论列表
     * @param $params
     * @return array
     */
    public static function VideoComments($params)
    {
        $query = Comments::find()->where(['video_id' => $params['id'],'parent_id' => 0]);

        $start = ($params['page'] - 1) * $params['per'];
        $q = clone $query;
        $count = $q->count();

        $data = $query->orderBy(['id' => SORT_DESC])->limit($params['per'])->offset($start)->asArray()->all();
        if($data){
            foreach($data as $index => &$item) {
                $uw = UserWechatService::getOneById($item['uw_id']);
                $item['uw_nickname'] = $uw->nickname;
                $item['uw_avatar'] = strstr($uw->avatar,'http') ? $uw->avatar : Yii::$app->params['apiHost'].$uw->avatar;
                //子评论
                $query = Comments::find()->where(['parent_id' => $item['id']])->orderBy(['id' => SORT_DESC]);
                $q = clone $query;
                $c = $q->count();
                $item['children']['count'] = $c;
                if($c != 0){
                    $first = $query->asArray()->one();
                    $u = UserWechatService::getOneById($first['uw_id']);
                    $first['uw_nickname'] = $u->nickname;
                    $first['uw_avatar'] = strstr($uw->avatar,'http') ? $uw->avatar : Yii::$app->params['apiHost'].$uw->avatar;
                    $item['children']['first'] = $first;
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
     * 子评论
     * @param $params
     * @return array
     */
    public static function children($params)
    {
        $query = Comments::find()->where(['parent_id' => $params['pid']]);

        $start = ($params['page'] - 1) * $params['per'];
        $q = clone $query;
        $count = $q->count();

        $data = $query->orderBy(['id' => SORT_DESC])->limit($params['per'])->offset($start)->asArray()->all();
        if($params['page'] == 1){
            array_shift($data);
        }
        if($data){
            foreach($data as $index => &$item) {
                $uw = UserWechatService::getOneById($item['uw_id']);
                $item['uw_nickname'] = $uw->nickname;
                $item['uw_avatar'] = strstr($uw->avatar,'http') ? $uw->avatar : Yii::$app->params['apiHost'].$uw->avatar;
            }
        }
        return [
            'count' => $count,
            'data' => $data,
            'pageCount' => ceil($count/$params['per'])
        ];
    }
}
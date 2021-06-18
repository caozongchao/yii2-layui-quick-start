<?php

namespace common\services;

use Yii;
use common\models\Msg;

class MsgService
{
    /**
     * 发送消息
     * @param $uwid
     * @param $videoId
     * @param $comment
     * @param $pid
     * @return bool
     * @throws \Exception
     */
    public static function send($uwid,$to,$msg)
    {
        $model = new Msg();
        $model->from = $uwid;
        $model->to = $to;
        $model->msg = $msg;
        if($model->save()){
            return true;
        }else{
            throw new \Exception('异常');
        }
    }

    /**
     * 阅读消息
     * @param $uwid
     * @param $id
     * @throws \Exception
     */
    public static function read($uwid,$id)
    {
        $msg = Msg::findOne($id);
        if($uwid != $msg->to){
            throw new \Exception('异常');
        }
        $msg->status = 1;
        $msg->save();
    }

    public static function msgList($uwid)
    {
        $count = Msg::find()->where(['to' => $uwid,'status' => 0])->count();
        if($count >= 10){
            $query = Msg::find()->where(['to' => $uwid,'status' => 0])->orderBy(['id' => SORT_DESC]);
            $data = $query->asArray()->all();
        }else{
            $data = Msg::find()->where(['to' => $uwid])->orderBy(['id' => SORT_DESC])->limit(10)->asArray()->all();
        }
        Yii::$app->db->createCommand()->update('msg', ['status' => 1], "`to` = $uwid")->execute();
        return $data;
    }

    public static function check($uwid)
    {
        $query = Msg::find()->where(['to' => $uwid,'status' => 0]);
        return $query->count();
    }
}
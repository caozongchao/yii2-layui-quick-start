<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "uw_collect_videos".
 *
 * @property int $id
 * @property int $uw_id 用户id
 * @property int $video_id 视频id
 */
class UwCollectVideos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'uw_collect_videos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uw_id', 'video_id'], 'required'],
            [['uw_id', 'video_id'], 'integer'],
            [['uw_id', 'video_id'], 'unique', 'targetAttribute' => ['uw_id', 'video_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uw_id' => '用户id',
            'video_id' => '视频id',
        ];
    }
}

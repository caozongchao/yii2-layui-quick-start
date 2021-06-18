<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "uw_like_videos".
 *
 * @property int $id
 * @property int $uw_id 用户
 * @property int $video_id 视频
 */
class UwLikeVideos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'uw_like_videos';
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
            'uw_id' => '用户',
            'video_id' => '视频',
        ];
    }
}

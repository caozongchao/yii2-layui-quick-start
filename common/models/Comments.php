<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comments".
 *
 * @property int $id
 * @property int $parent_id
 * @property int $uw_id
 * @property int $video_id 视频id
 * @property string $comment 评论内容
 * @property string $created_at
 */
class Comments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'uw_id', 'video_id'], 'integer'],
            [['uw_id', 'video_id', 'comment'], 'required'],
            [['comment'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'uw_id' => 'Uw ID',
            'video_id' => '视频id',
            'comment' => '评论内容',
            'created_at' => 'Created At',
        ];
    }
}

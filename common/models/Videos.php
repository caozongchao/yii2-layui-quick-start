<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "videos".
 *
 * @property int $id
 * @property int $uw_id 发布者id
 * @property string $video_desc 视频描述
 * @property string $video_path 视频存放的路径
 * @property float|null $video_seconds 视频秒数
 * @property int|null $video_width 视频宽度
 * @property int|null $video_height 视频高度
 * @property string|null $cover_path 视频封面图
 * @property int $like_counts 点赞量
 * @property int $collect_counts 收藏量
 * @property int $comments 评论数
 * @property int $status 视频状态：0禁用1启用
 * @property string $created_at 创建时间
 * @property int $type 0图片1视频
 * @property int $flag 视频是否已经计算积分，0否1是
 * @property int $top 置顶
 */
class Videos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'videos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uw_id', 'video_desc', 'video_path'], 'required'],
            [['uw_id', 'video_width', 'video_height', 'like_counts', 'collect_counts', 'comments', 'status', 'type', 'flag', 'top'], 'integer'],
            [['video_seconds'], 'number'],
            [['created_at'], 'safe'],
            [['video_desc'], 'string', 'max' => 128],
            [['video_path'], 'string', 'max' => 1000],
            [['cover_path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uw_id' => '发布者id',
            'video_desc' => '视频描述',
            'video_path' => '视频存放的路径',
            'video_seconds' => '视频秒数',
            'video_width' => '视频宽度',
            'video_height' => '视频高度',
            'cover_path' => '视频封面图',
            'like_counts' => '点赞量',
            'collect_counts' => '收藏量',
            'comments' => '评论数',
            'status' => '视频状态：0禁用1启用',
            'created_at' => '创建时间',
            'type' => '0图片1视频',
            'flag' => '积分标记',
            'top' => '置顶',
        ];
    }
}

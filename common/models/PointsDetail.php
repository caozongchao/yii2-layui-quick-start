<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "points_detail".
 *
 * @property int $id
 * @property int $uw_id 用户id
 * @property int $type 0获取1消费
 * @property int $num 积分数目
 * @property int $sum 积分总数
 * @property string $desc 描述
 * @property string|null $created_at
 */
class PointsDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'points_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uw_id', 'num', 'sum', 'desc'], 'required'],
            [['uw_id', 'type', 'num', 'sum'], 'integer'],
            [['created_at'], 'safe'],
            [['desc'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uw_id' => 'Uw ID',
            'type' => 'Type',
            'num' => 'Num',
            'sum' => 'Sum',
            'desc' => 'Desc',
            'created_at' => 'Created At',
        ];
    }
}

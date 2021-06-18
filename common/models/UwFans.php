<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "uw_fans".
 *
 * @property int $id
 * @property int $uw_id 用户
 * @property int $fan_id 粉丝
 */
class UwFans extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'uw_fans';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uw_id', 'fan_id'], 'required'],
            [['uw_id', 'fan_id'], 'integer'],
            [['uw_id', 'fan_id'], 'unique', 'targetAttribute' => ['uw_id', 'fan_id']],
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
            'fan_id' => '粉丝',
        ];
    }
}

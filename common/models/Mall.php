<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mall".
 *
 * @property int $id
 * @property int $uw_id
 * @property string $appid 小程序appid
 * @property int $status 0未审核1审核通过2审核不通过
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Mall extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mall';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uw_id', 'appid'], 'required'],
            [['uw_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['appid'], 'string', 'max' => 32],
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
            'appid' => '小程序appid',
            'status' => '0未审核1审核通过2审核不通过',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

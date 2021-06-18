<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "live".
 *
 * @property int $id
 * @property int $uw_id
 * @property int $room_id 小程序appid
 * @property string $qrcode_url 小程序直播" 小程序码
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Live extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'live';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uw_id', 'room_id', 'qrcode_url'], 'required'],
            [['uw_id', 'room_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['qrcode_url'], 'string', 'max' => 255],
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
            'room_id' => '小程序appid',
            'qrcode_url' => '小程序直播\" 小程序码',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

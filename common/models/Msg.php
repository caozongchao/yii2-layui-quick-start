<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "msg".
 *
 * @property int $id
 * @property int $from
 * @property int $to
 * @property string $msg
 * @property string|null $created_at
 * @property int $status 0未读1已读
 */
class Msg extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'msg';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from', 'to', 'msg'], 'required'],
            [['from', 'to', 'status'], 'integer'],
            [['created_at'], 'safe'],
            [['msg'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from' => 'From',
            'to' => 'To',
            'msg' => 'Msg',
            'created_at' => 'Created At',
            'status' => '0未读1已读',
        ];
    }
}

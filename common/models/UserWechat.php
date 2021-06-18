<?php

namespace common\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user_wechat".
 *
 * @property int $id
 * @property string $openid 微信openid
 * @property string $unionid 微信unionid
 * @property string $nickname 微信昵称
 * @property string $avatar 微信头像
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property int $fans_counts 粉丝
 * @property int $follow_counts 关注
 * @property int $receive_like_counts 被点赞量
 * @property int $receive_collect_counts 被收藏量
 * @property int $collect_counts 收藏量
 * @property string $signature 签名
 * @property int $sex 性别，0未设置1男2女
 * @property string $area 地区
 * @property string $access_token token的md5
 * @property int $status 状态，0禁用1启用
 * @property int $points 积分
 */
class UserWechat extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_wechat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['openid', 'unionid', 'nickname'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['fans_counts', 'follow_counts', 'receive_like_counts', 'receive_collect_counts', 'collect_counts', 'sex', 'status', 'points'], 'integer'],
            [['openid', 'unionid'], 'string', 'max' => 64],
            [['nickname'], 'string', 'max' => 512],
            [['avatar'], 'string', 'max' => 256],
            [['signature'], 'string', 'max' => 255],
            [['area'], 'string', 'max' => 20],
            [['access_token'], 'string', 'max' => 32],
            [['openid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => '微信openid',
            'unionid' => '微信unionid',
            'nickname' => '微信昵称',
            'avatar' => '微信头像',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'fans_counts' => '粉丝',
            'follow_counts' => '关注',
            'receive_like_counts' => '被点赞量',
            'receive_collect_counts' => '被收藏量',
            'collect_counts' => '收藏量',
            'signature' => '签名',
            'sex' => '性别，0未设置1男2女',
            'area' => '地区',
            'access_token' => 'token的md5',
            'status' => '状态，0禁用1启用',
            'points' => '积分',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => 1]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['access_token' => md5($token)]);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
}

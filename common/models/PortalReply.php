<?php

/**
 * 模块 A 留言回复 ActiveRecord 模型。
 *
 * @author wuyi
 * @date 2026-11-19
 */

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class PortalReply extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%wc_portal_reply}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function rules()
    {
        return [
            [['message_id', 'content'], 'required'],
            [['content'], 'trim'],
            [['content'], 'string'],
            [['message_id', 'admin_user_id'], 'integer', 'min' => 1],
            [['message_id'], 'unique'],
            [
                ['message_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => PortalMessage::className(),
                'targetAttribute' => ['message_id' => 'id'],
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '回复编号',
            'message_id' => '对应留言',
            'content' => '回复内容',
            'admin_user_id' => '回复管理员用户编号',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function getMessage()
    {
        return $this->hasOne(PortalMessage::className(), ['id' => 'message_id']);
    }
}

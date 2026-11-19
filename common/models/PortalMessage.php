<?php

/**
 * 模块 A 访客留言 ActiveRecord 模型。
 *
 * @author wuyi
 * @date 2026-11-19
 */

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class PortalMessage extends ActiveRecord
{
    const SCENARIO_PUBLIC_CREATE = 'publicCreate';

    const STATUS_PENDING = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_HIDDEN = 2;

    public static function tableName()
    {
        return '{{%wc_portal_message}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_PUBLIC_CREATE] = ['display_name', 'content'];

        return $scenarios;
    }

    public function rules()
    {
        return [
            [['display_name', 'content'], 'trim'],
            [['display_name', 'content'], 'required'],
            [['content'], 'string'],
            [['display_name'], 'string', 'max' => 60],
            [['status'], 'integer'],
            [['status'], 'default', 'value' => self::STATUS_PENDING],
            [
                ['status'],
                'in',
                'range' => [self::STATUS_PENDING, self::STATUS_PUBLISHED, self::STATUS_HIDDEN],
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '留言编号',
            'display_name' => '留言昵称',
            'content' => '留言内容',
            'status' => '审核状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function getReply()
    {
        return $this->hasOne(PortalReply::className(), ['message_id' => 'id']);
    }

    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => '待审核',
            self::STATUS_PUBLISHED => '已发布',
            self::STATUS_HIDDEN => '已隐藏',
        ];
    }

    public function getStatusLabel()
    {
        $options = self::getStatusOptions();

        return isset($options[$this->status]) ? $options[$this->status] : '未知状态';
    }

    public function isPublished()
    {
        return (int) $this->status === self::STATUS_PUBLISHED;
    }
}

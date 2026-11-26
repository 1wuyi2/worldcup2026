<?php

/**
 * 模块 A 团队及个人留言 ActiveRecord 模型。
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
    const SCENARIO_ADMIN_CREATE = 'adminCreate';
    const SCENARIO_ADMIN_UPDATE = 'adminUpdate';

    const STATUS_PENDING = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_HIDDEN = 2;

    const SOURCE_VISITOR = 0;
    const SOURCE_ADMIN = 1;

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
        $scenarios[self::SCENARIO_PUBLIC_CREATE] = [
            'display_name',
            'content',
            '!member_id',
            '!status',
            '!source_type',
            '!created_by_user_id',
        ];
        $scenarios[self::SCENARIO_ADMIN_CREATE] = [
            '!display_name',
            'content',
            'member_id',
            'status',
            '!source_type',
            '!created_by_user_id',
        ];
        $scenarios[self::SCENARIO_ADMIN_UPDATE] = $scenarios[self::SCENARIO_ADMIN_CREATE];

        return $scenarios;
    }

    public function rules()
    {
        return [
            [['display_name', 'content'], 'trim'],
            [['display_name', 'content'], 'required'],
            [['content'], 'string'],
            [['display_name'], 'string', 'max' => 60],
            [['status', 'source_type'], 'integer'],
            [['member_id', 'created_by_user_id'], 'integer', 'min' => 1],
            [['status'], 'default', 'value' => self::STATUS_PENDING],
            [['source_type'], 'default', 'value' => self::SOURCE_VISITOR],
            [
                ['status'],
                'in',
                'range' => [self::STATUS_PENDING, self::STATUS_PUBLISHED, self::STATUS_HIDDEN],
            ],
            [
                ['source_type'],
                'in',
                'range' => [self::SOURCE_VISITOR, self::SOURCE_ADMIN],
            ],
            [
                ['member_id'],
                'exist',
                'skipOnEmpty' => true,
                'skipOnError' => true,
                'targetClass' => PortalMember::className(),
                'targetAttribute' => ['member_id' => 'id'],
            ],
            [
                ['created_by_user_id'],
                'exist',
                'skipOnEmpty' => true,
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['created_by_user_id' => 'id'],
                'on' => self::SCENARIO_ADMIN_CREATE,
            ],
            [['source_type'], 'validateSourceIdentity'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '留言编号',
            'display_name' => '发布者显示名称',
            'content' => '留言内容',
            'member_id' => '发布成员',
            'source_type' => '留言来源',
            'created_by_user_id' => '后台操作人员',
            'status' => '审核状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function getReply()
    {
        return $this->hasOne(PortalReply::className(), ['message_id' => 'id']);
    }

    public function getMember()
    {
        return $this->hasOne(PortalMember::className(), ['id' => 'member_id']);
    }

    public function getCreatedByUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by_user_id']);
    }

    public function validateSourceIdentity($attribute)
    {
        $memberIdIsEmpty = $this->member_id === null || $this->member_id === '';
        $userIdIsEmpty = $this->created_by_user_id === null || $this->created_by_user_id === '';

        if ((int) $this->source_type === self::SOURCE_VISITOR) {
            if (!$memberIdIsEmpty) {
                $this->addError('member_id', '历史访客留言不能设置团队成员发布身份。');
            }
            if (!$userIdIsEmpty) {
                $this->addError('created_by_user_id', '历史访客留言不能设置后台操作人员。');
            }
            return;
        }

        if ((int) $this->source_type === self::SOURCE_ADMIN && $userIdIsEmpty && $this->isNewRecord) {
            $this->addError('created_by_user_id', '后台发布留言必须记录实际操作人员。');
        }
    }

    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => '待审核',
            self::STATUS_PUBLISHED => '已发布',
            self::STATUS_HIDDEN => '已隐藏',
        ];
    }

    public static function getSourceTypeOptions()
    {
        return [
            self::SOURCE_VISITOR => '历史访客留言',
            self::SOURCE_ADMIN => '后台主动发布',
        ];
    }

    public static function findPublishedAdmin()
    {
        return static::find()->andWhere([
            'source_type' => self::SOURCE_ADMIN,
            'status' => self::STATUS_PUBLISHED,
        ]);
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

    public function isVisitorMessage()
    {
        return (int) $this->source_type === self::SOURCE_VISITOR;
    }

    public function isAdminMessage()
    {
        return (int) $this->source_type === self::SOURCE_ADMIN;
    }

    public function getPublisherLabel()
    {
        if ($this->isVisitorMessage()) {
            return $this->display_name;
        }

        if ($this->member !== null) {
            return $this->member->name;
        }

        return $this->display_name;
    }
}

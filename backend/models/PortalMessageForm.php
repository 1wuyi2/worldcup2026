<?php

/**
 * 模块 A 后台团队及个人留言发布表单模型。
 *
 * @author wuyi
 * @date 2026-11-26
 */

namespace backend\models;

use common\models\PortalMember;
use common\models\PortalMessage;
use yii\base\Model;

class PortalMessageForm extends Model
{
    const PUBLISHER_TEAM = 'team';
    const PUBLISHER_MEMBER = 'member';

    public $publisher_type;
    public $member_id;
    public $content;
    public $status;

    public function __construct(PortalMessage $message = null, $config = [])
    {
        if ($message === null) {
            $this->publisher_type = self::PUBLISHER_TEAM;
            $this->status = PortalMessage::STATUS_PENDING;
        } else {
            $this->publisher_type = $message->member_id === null
                ? self::PUBLISHER_TEAM
                : self::PUBLISHER_MEMBER;
            $this->member_id = $message->member_id;
            $this->content = $message->content;
            $this->status = $message->status;
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['publisher_type', 'content'], 'required'],
            [['publisher_type', 'content'], 'trim'],
            [['content'], 'string'],
            [['publisher_type'], 'in', 'range' => [self::PUBLISHER_TEAM, self::PUBLISHER_MEMBER]],
            [['member_id'], 'integer', 'min' => 1],
            [['member_id'], 'validateMemberAssociation', 'skipOnEmpty' => false],
            [['status'], 'integer'],
            [['status'], 'in', 'range' => array_keys(PortalMessage::getStatusOptions())],
        ];
    }

    public function attributeLabels()
    {
        return [
            'publisher_type' => '发布身份',
            'member_id' => '发布成员',
            'content' => '留言正文',
            'status' => '发布状态',
        ];
    }

    public static function getPublisherTypeOptions()
    {
        return [
            self::PUBLISHER_TEAM => '团队身份',
            self::PUBLISHER_MEMBER => '成员身份',
        ];
    }

    public function validateMemberAssociation($attribute)
    {
        $memberIdIsEmpty = $this->$attribute === null || $this->$attribute === '';

        if ($this->publisher_type === self::PUBLISHER_TEAM) {
            if (!$memberIdIsEmpty) {
                $this->addError($attribute, '团队身份发布时不能选择成员。');
            }
            return;
        }

        if ($this->publisher_type === self::PUBLISHER_MEMBER) {
            if ($memberIdIsEmpty) {
                $this->addError($attribute, '成员身份发布时必须选择真实成员。');
                return;
            }

            if (PortalMember::findOne($this->$attribute) === null) {
                $this->addError($attribute, '所选发布成员不存在。');
            }
        }
    }
}

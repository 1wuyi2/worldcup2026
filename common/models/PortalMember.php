<?php

/**
 * 模块 A 团队成员 ActiveRecord 模型。
 *
 * @author wuyi
 * @date 2026-11-19
 */

namespace common\models;

use common\validators\FrontendImagePathValidator;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class PortalMember extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%wc_portal_member}}';
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
            [['team_id', 'name'], 'required'],
            [['name', 'student_no', 'role', 'avatar_path'], 'trim'],
            [['team_id'], 'integer', 'min' => 1],
            [['sort_order'], 'integer'],
            [['sort_order'], 'default', 'value' => 0],
            [['bio'], 'string'],
            [['name', 'role'], 'string', 'max' => 60],
            [['student_no'], 'string', 'max' => 20],
            [['avatar_path'], 'string', 'max' => 255],
            [['avatar_path'], FrontendImagePathValidator::className(), 'skipOnEmpty' => true],
            [
                ['team_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => PortalTeam::className(),
                'targetAttribute' => ['team_id' => 'id'],
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '成员编号',
            'team_id' => '所属团队',
            'name' => '姓名',
            'student_no' => '学号',
            'role' => '成员职责',
            'bio' => '个人介绍',
            'avatar_path' => '头像路径',
            'sort_order' => '展示顺序',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function getTeam()
    {
        return $this->hasOne(
            PortalTeam::className(),
            ['id' => 'team_id']
        );
    }

    public function getPersonalAssignments()
    {
        return $this->hasMany(
            PortalAssignment::className(),
            ['member_id' => 'id']
        )
            ->andWhere([
                'category' => PortalAssignment::CATEGORY_PERSONAL
            ])
            ->orderBy([
                'created_at' => SORT_DESC,
                'id' => SORT_DESC
            ]);
    }

    /**
     * 获取该成员已发布的个人作业。
     */
    public function getPublishedPersonalAssignments()
    {
        return $this->getPersonalAssignments()
            ->andWhere([
                'status' => PortalAssignment::STATUS_PUBLISHED
            ]);
    }

    public function getMessages()
    {
        return $this->hasMany(
            PortalMessage::className(),
            ['member_id' => 'id']
        )
            ->andWhere([
                'source_type' => PortalMessage::SOURCE_ADMIN
            ])
            ->orderBy([
                'created_at' => SORT_DESC,
                'id' => SORT_DESC
            ]);
    }

    public function getPublishedMessages()
    {
        return $this->getMessages()
            ->andWhere([
                'status' => PortalMessage::STATUS_PUBLISHED
            ]);
    }
}
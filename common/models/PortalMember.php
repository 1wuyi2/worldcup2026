<?php

/**
 * 模块 A 团队成员 ActiveRecord 模型。
 *
 * @author wuyi
 * @date 2026-11-19
 */

namespace common\models;

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
            [['name', 'student_no', 'role'], 'trim'],
            [['team_id'], 'integer', 'min' => 1],
            [['sort_order'], 'integer'],
            [['sort_order'], 'default', 'value' => 0],
            [['bio'], 'string'],
            [['name', 'role'], 'string', 'max' => 60],
            [['student_no'], 'string', 'max' => 20],
            [['avatar_path'], 'string', 'max' => 255],
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
        return $this->hasOne(PortalTeam::className(), ['id' => 'team_id']);
    }
}

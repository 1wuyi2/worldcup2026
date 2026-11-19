<?php

/**
 * 模块 A 团队资料 ActiveRecord 模型。
 *
 * @author wuyi
 * @date 2026-11-19
 */

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class PortalTeam extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%wc_portal_team}}';
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
            [['name'], 'trim'],
            [['name'], 'required'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['logo_path'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '团队编号',
            'name' => '团队名称',
            'description' => '团队介绍',
            'logo_path' => '展示图片路径',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function getMembers()
    {
        return $this->hasMany(PortalMember::className(), ['team_id' => 'id'])
            ->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC]);
    }
}

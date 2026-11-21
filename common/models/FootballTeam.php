<?php

/**
 * 国家队及其球员。
 * @author lzs
 * @date 2026-11-20
 */

namespace common\models;

class FootballTeam extends FootballRecord
{
    public static function tableName()
    {
        return '{{%wc_football_team}}';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name', 'country_code', 'group_name'], 'trim'],
            [['name', 'country_code'], 'required'],
            ['country_code', 'filter', 'filter' => 'strtoupper', 'skipOnEmpty' => true],
            ['name', 'string', 'max' => 100],
            [['country_code', 'group_name'], 'string', 'max' => 10],
            ['country_code', 'unique'],
            ['group_name', 'default'],
        ]);
    }

    public function attributeLabels()
    {
        return [
            'name' => '球队名称',
            'country_code' => '球队代码',
            'group_name' => '小组',
            'source_url' => '资料来源',
        ];
    }

    public function getPlayers()
    {
        return $this->hasMany(FootballPlayer::class, ['team_id' => 'id']);
    }
}

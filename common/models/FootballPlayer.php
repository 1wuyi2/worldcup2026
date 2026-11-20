<?php

/**
 * 球员资料和所属球队。
 * @author lzs
 * @date 2026-11-20
 */

namespace common\models;

class FootballPlayer extends FootballRecord
{
    public static function tableName()
    {
        return '{{%wc_football_player}}';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name', 'position'], 'trim'],
            [['team_id', 'name'], 'required'],
            ['team_id', 'integer', 'min' => 1],
            ['team_id', 'exist', 'targetClass' => FootballTeam::class, 'targetAttribute' => 'id'],
            ['name', 'string', 'max' => 100],
            ['position', 'string', 'max' => 30],
            [['number', 'position'], 'default'],
            ['number', 'integer', 'min' => 0, 'max' => 4294967295],
        ]);
    }

    public function attributeLabels()
    {
        return [
            'team_id' => '所属球队',
            'name' => '球员姓名',
            'number' => '球衣号码',
            'position' => '位置',
            'source_url' => '资料来源',
        ];
    }

    public function getTeam()
    {
        return $this->hasOne(FootballTeam::class, ['id' => 'team_id']);
    }
}

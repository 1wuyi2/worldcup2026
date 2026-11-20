<?php

/**
 * 比赛场馆。
 * @author lzs
 * @date 2026-11-20
 */

namespace common\models;

class FootballStadium extends FootballRecord
{
    public static function tableName()
    {
        return '{{%wc_football_stadium}}';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name', 'city'], 'trim'],
            [['name', 'city'], 'required'],
            [['name', 'city'], 'string', 'max' => 100],
            ['capacity', 'default'],
            ['capacity', 'integer', 'min' => 0, 'max' => 4294967295],
        ]);
    }

    public function attributeLabels()
    {
        return [
            'name' => '场馆名称',
            'city' => '城市',
            'capacity' => '容量',
            'source_url' => '资料来源',
        ];
    }
}

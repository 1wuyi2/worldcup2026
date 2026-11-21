<?php

/**
 * 模块 C 记录共用的时间戳和来源网址。
 * @author lzs
 * @date 2026-11-20
 */

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

abstract class FootballRecord extends ActiveRecord
{
    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function rules()
    {
        return [
            ['source_url', 'trim'],
            ['source_url', 'default'],
            ['source_url', 'string', 'max' => 500],
            ['source_url', 'url', 'validSchemes' => ['http', 'https']],
        ];
    }
}

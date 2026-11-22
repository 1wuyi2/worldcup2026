<?php

/**
 * 模块 B 新闻分类及启用状态。
 * @author XPC
 * @date 2026-11-22
 */

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class NewsCategory extends ActiveRecord
{
    public const STATUS_DISABLED = 0;
    public const STATUS_ENABLED = 1;

    public static function tableName()
    {
        return '{{%wc_news_category}}';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'max' => 60],
            ['name', 'unique'],
            ['sort_order', 'default', 'value' => 0],
            ['sort_order', 'integer', 'min' => -2147483648, 'max' => 2147483647],
            ['status', 'default', 'value' => self::STATUS_ENABLED],
            ['status', 'in', 'range' => [self::STATUS_DISABLED, self::STATUS_ENABLED]],
        ];
    }

    public function attributeLabels()
    {
        return ['id' => '分类编号', 'name' => '分类名称', 'sort_order' => '排序', 'status' => '状态'];
    }

    public function getArticles()
    {
        return $this->hasMany(NewsArticle::class, ['category_id' => 'id']);
    }

    public function getStatusLabel()
    {
        return (int) $this->status === self::STATUS_ENABLED ? '启用' : '停用';
    }
}

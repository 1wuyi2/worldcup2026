<?php

/**
 * 模块 B 新闻标签及其文章关联。
 * @author XPC
 * @date 2026-11-22
 */

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class NewsTag extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%wc_news_tag}}';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function rules()
    {
        return [
            [['name', 'slug'], 'trim'],
            [['name', 'slug'], 'required'],
            ['name', 'string', 'max' => 60],
            ['slug', 'string', 'max' => 80],
            ['slug', 'match', 'pattern' => '/^[a-z0-9]+(?:-[a-z0-9]+)*$/D', 'message' => '标签标识使用小写英文字母、数字及单个连接符，例如 world-cup。'],
            ['slug', 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return ['id' => '标签编号', 'name' => '标签名称', 'slug' => '标签标识'];
    }

    public function getArticles()
    {
        return $this->hasMany(NewsArticle::class, ['id' => 'article_id'])
            ->viaTable(NewsArticleTag::tableName(), ['tag_id' => 'id']);
    }
}

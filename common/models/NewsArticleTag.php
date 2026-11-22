<?php

/**
 * 模块 B 文章标签关联，联合主键防止重复绑定。
 * @author XPC
 * @date 2026-11-22
 */

namespace common\models;

use yii\db\ActiveRecord;

class NewsArticleTag extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%wc_news_article_tag}}';
    }

    public function rules()
    {
        return [
            [['article_id', 'tag_id'], 'required'],
            [['article_id', 'tag_id'], 'integer', 'min' => 1],
            [['article_id', 'tag_id'], 'unique', 'targetAttribute' => ['article_id', 'tag_id']],
            ['article_id', 'exist', 'targetClass' => NewsArticle::class, 'targetAttribute' => 'id'],
            ['tag_id', 'exist', 'targetClass' => NewsTag::class, 'targetAttribute' => 'id'],
        ];
    }

    public function getArticle()
    {
        return $this->hasOne(NewsArticle::class, ['id' => 'article_id']);
    }

    public function getTag()
    {
        return $this->hasOne(NewsTag::class, ['id' => 'tag_id']);
    }
}

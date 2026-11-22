<?php

/**
 * 模块 B 列表筛选参数及查询组合，公开查询固定使用发布范围。
 * @author XPC
 * @date 2026-11-22
 */

namespace common\models;

use yii\base\Model;

class NewsSearch extends Model
{
    public $q = '';
    public $category_id;
    public $tag_id;
    public $status;

    public function rules()
    {
        return [
            ['q', 'trim'],
            ['q', 'string', 'max' => 200],
            [['category_id', 'tag_id', 'status'], 'default'],
            [['category_id', 'tag_id'], 'integer', 'min' => 1],
            ['status', 'in', 'range' => array_keys(NewsArticle::STATUS_LABELS)],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['q', 'category_id', 'tag_id', 'status'],
            'public' => ['q', 'category_id', 'tag_id'],
        ];
    }

    public function query($public = true)
    {
        $query = $public ? NewsArticle::publishedQuery() : NewsArticle::find()->alias('article');
        $query->with(['category', 'tags'])
            ->andFilterWhere(['like', 'article.title', $this->q])
            ->andFilterWhere(['article.category_id' => $this->category_id]);
        if (!$public) {
            $query->andFilterWhere(['article.status' => $this->status]);
        }
        if ($this->tag_id !== null && $this->tag_id !== '') {
            $query->andWhere(['article.id' => NewsArticleTag::find()->select('article_id')->where(['tag_id' => $this->tag_id])]);
        }
        return $query;
    }
}

<?php

/**
 * 模块 B 文章编辑表单，在同一事务内保存文章与标签集合。
 * @author XPC
 * @date 2026-11-22
 */

namespace backend\models;

use common\models\NewsArticle;
use common\models\NewsArticleTag;
use common\models\NewsTag;
use yii\db\StaleObjectException;

class NewsArticleForm extends NewsArticle
{
    public $tagIds = [];

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['tagIds', 'validateTagIds', 'skipOnEmpty' => false],
        ]);
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        foreach ($scenarios as &$attributes) {
            // Yii2 的 ! 前缀保留校验，但禁止批量赋值覆盖发布时间。
            $attributes = array_map(static function ($attribute) {
                return $attribute === 'published_at' ? '!published_at' : $attribute;
            }, $attributes);
        }
        unset($attributes);
        return $scenarios;
    }

    public function attributeLabels()
    {
        return parent::attributeLabels() + ['tagIds' => '新闻标签'];
    }

    public function loadTags()
    {
        $this->tagIds = $this->isNewRecord ? [] : $this->getTags()->select('id')->column();
    }

    public function validateTagIds($attribute)
    {
        // HTML 多选框取消所有选项时提交空字符串，按空标签集合处理。
        if ($this->tagIds === '' || $this->tagIds === null) {
            $this->tagIds = [];
        }
        if (!is_array($this->tagIds) || count($this->tagIds) > 100) {
            $this->addError($attribute, '请选择有效标签，一篇文章最多绑定 100 个标签。');
            return;
        }
        foreach ($this->tagIds as $id) {
            if (!is_scalar($id) || !preg_match('/^[1-9][0-9]{0,19}$/D', (string) $id)) {
                $this->addError($attribute, '标签编号无效。');
                return;
            }
        }
        $ids = array_values(array_unique(array_map('strval', $this->tagIds)));
        if ($ids !== [] && (int) NewsTag::find()->where(['id' => $ids])->count() !== count($ids)) {
            $this->addError($attribute, '部分标签已被删除，请刷新后重新选择。');
            return;
        }
        $this->tagIds = $ids;
    }

    public function saveWithTags()
    {
        if (!$this->validate()) {
            return false;
        }
        return static::getDb()->transaction(function () {
            if (!$this->isNewRecord) {
                $exists = static::getDb()->createCommand(
                    'SELECT id FROM {{%wc_news_article}} WHERE id = :id FOR UPDATE', [':id' => $this->id]
                )->queryScalar();
                if ($exists === false) {
                    throw new StaleObjectException('文章已被删除，请返回列表。');
                }
            }
            if (!$this->save(false)) {
                return false;
            }
            NewsArticleTag::deleteAll(['article_id' => $this->id]);
            if ($this->tagIds !== []) {
                $rows = array_map(function ($id) { return [$this->id, $id]; }, $this->tagIds);
                static::getDb()->createCommand()->batchInsert(NewsArticleTag::tableName(), ['article_id', 'tag_id'], $rows)->execute();
            }
            unset($this->tags);
            return true;
        });
    }
}

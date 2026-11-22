<?php

/**
 * 模块 B 新闻文章、发布规则及公开查询范围。
 * @author XPC
 * @date 2026-11-22
 */

namespace common\models;

use DateTimeImmutable;
use DateTimeZone;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class NewsArticle extends ActiveRecord
{
    public const STATUS_DRAFT = 0;
    public const STATUS_PUBLISHED = 1;
    public const STATUS_WITHDRAWN = 2;
    public const STATUS_LABELS = [0 => '草稿', 1 => '已发布', 2 => '已撤稿'];

    public static function tableName()
    {
        return '{{%wc_news_article}}';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function rules()
    {
        return [
            [['title', 'summary', 'body', 'source_name', 'source_url'], 'trim'],
            [['category_id', 'title', 'body'], 'required'],
            ['category_id', 'integer', 'min' => 1],
            ['category_id', 'exist', 'targetClass' => NewsCategory::class, 'targetAttribute' => 'id'],
            ['category_id', 'validatePublishCategory'],
            ['title', 'string', 'max' => 200],
            ['summary', 'string', 'max' => 10000],
            ['body', 'string', 'max' => 1000000],
            ['source_name', 'string', 'max' => 100],
            ['source_url', 'string', 'max' => 500],
            ['source_url', 'url', 'validSchemes' => ['http', 'https']],
            [['summary', 'source_name', 'source_url', 'published_at'], 'default'],
            ['published_at', 'integer', 'min' => 0, 'max' => 4294967295],
            ['status', 'default', 'value' => self::STATUS_DRAFT],
            ['status', 'in', 'range' => array_keys(self::STATUS_LABELS)],
        ];
    }

    public function validatePublishCategory($attribute)
    {
        if ((int) $this->status === self::STATUS_PUBLISHED) {
            $enabled = NewsCategory::find()->where(['id' => $this->category_id, 'status' => NewsCategory::STATUS_ENABLED])->exists();
            if (!$enabled) {
                $this->addError($attribute, '发布文章前，请选择启用的新闻分类。');
            }
        }
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ((int) $this->status === self::STATUS_PUBLISHED
            && ($insert || (int) $this->getOldAttribute('status') !== self::STATUS_PUBLISHED || $this->published_at === null)) {
            $this->published_at = time();
        }
        return true;
    }

    public static function publishedQuery()
    {
        return static::find()->alias('article')
            ->innerJoin(['category' => NewsCategory::tableName()], '[[category.id]] = [[article.category_id]]')
            ->andWhere(['article.status' => self::STATUS_PUBLISHED, 'category.status' => NewsCategory::STATUS_ENABLED])
            ->andWhere(['<=', 'article.published_at', time()]);
    }

    public function getCategory()
    {
        return $this->hasOne(NewsCategory::class, ['id' => 'category_id']);
    }

    public function getTags()
    {
        return $this->hasMany(NewsTag::class, ['id' => 'tag_id'])
            ->viaTable(NewsArticleTag::tableName(), ['article_id' => 'id'])
            ->orderBy(['name' => SORT_ASC, 'id' => SORT_ASC]);
    }

    public function getStatusLabel()
    {
        return self::STATUS_LABELS[$this->status] ?? '未知状态';
    }

    public function getPublishedAtLabel()
    {
        return $this->published_at === null ? '尚未发布'
            : (new DateTimeImmutable('@' . $this->published_at))->setTimezone(new DateTimeZone('Asia/Shanghai'))->format('Y-m-d H:i');
    }

    public function attributeLabels()
    {
        return [
            'id' => '文章编号', 'category_id' => '新闻分类', 'title' => '标题',
            'summary' => '摘要', 'body' => '正文', 'source_name' => '来源名称',
            'source_url' => '来源链接', 'published_at' => '发布时间（北京时间）',
            'status' => '发布状态', 'created_at' => '创建时间', 'updated_at' => '更新时间',
        ];
    }
}

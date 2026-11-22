<?php

/**
 * 模块 B 新闻分类、文章、标签及多对多关联表。
 * @author XPC
 * @date 2026-11-22
 */

use yii\db\Migration;

class m261122_230000_create_news_tables extends Migration
{
    public function safeUp()
    {
        $options = 'ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
        $this->createTable('{{%wc_news_category}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'name' => $this->string(60)->notNull()->unique(),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(1),
        ] + $this->timestamps(), $options);

        $this->createTable('{{%wc_news_article}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'category_id' => $this->bigInteger()->unsigned()->notNull(),
            'title' => $this->string(200)->notNull(),
            'summary' => $this->text(),
            'body' => 'MEDIUMTEXT NOT NULL',
            'source_name' => $this->string(100),
            'source_url' => $this->string(500),
            'published_at' => $this->integer()->unsigned(),
            'status' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
        ] + $this->timestamps(), $options);
        $this->createIndex('idx-news-article-category', '{{%wc_news_article}}', 'category_id');
        $this->createIndex('idx-news-article-published', '{{%wc_news_article}}', ['status', 'published_at', 'id']);
        $this->addForeignKey('fk-news-article-category', '{{%wc_news_article}}', 'category_id', '{{%wc_news_category}}', 'id', 'RESTRICT', 'RESTRICT');

        $this->createTable('{{%wc_news_tag}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'name' => $this->string(60)->notNull(),
            'slug' => $this->string(80)->notNull()->unique(),
        ] + $this->timestamps(), $options);

        $this->createTable('{{%wc_news_article_tag}}', [
            'article_id' => $this->bigInteger()->unsigned()->notNull(),
            'tag_id' => $this->bigInteger()->unsigned()->notNull(),
            'PRIMARY KEY ([[article_id]], [[tag_id]])',
        ], $options);
        $this->createIndex('idx-news-article-tag-tag', '{{%wc_news_article_tag}}', 'tag_id');
        $this->addForeignKey('fk-news-article-tag-article', '{{%wc_news_article_tag}}', 'article_id', '{{%wc_news_article}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-news-article-tag-tag', '{{%wc_news_article_tag}}', 'tag_id', '{{%wc_news_tag}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('{{%wc_news_article_tag}}');
        $this->dropTable('{{%wc_news_tag}}');
        $this->dropTable('{{%wc_news_article}}');
        $this->dropTable('{{%wc_news_category}}');
    }

    private function timestamps()
    {
        return [
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ];
    }
}

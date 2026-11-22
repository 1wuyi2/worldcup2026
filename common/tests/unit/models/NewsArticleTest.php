<?php

/**
 * 模块 B 发布范围、标签事务、删除关联和输入校验测试。
 * @author XPC
 * @date 2026-11-22
 */

namespace common\tests\unit\models;

use backend\models\NewsArticleForm;
use Codeception\Test\Unit;
use common\models\NewsArticle;
use common\models\NewsArticleTag;
use common\models\NewsCategory;
use common\models\NewsSearch;
use common\models\NewsTag;
use Yii;

class NewsArticleTest extends Unit
{
    private $transaction;
    private $category;

    protected function _before()
    {
        $this->transaction = Yii::$app->db->beginTransaction();
        $this->category = new NewsCategory(['name' => '新闻测试-' . bin2hex(random_bytes(5))]);
        $this->assertTrue($this->category->save(), json_encode($this->category->errors));
    }

    protected function _after()
    {
        if ($this->transaction !== null && $this->transaction->isActive) {
            $this->transaction->rollBack();
        }
    }

    public function testDraftPublishWithdrawAndDisabledCategory()
    {
        $article = $this->article();
        $this->assertTrue($article->saveWithTags(), json_encode($article->errors));
        $this->assertFalse($this->visible($article));
        $this->assertNull($article->published_at);

        $article->status = NewsArticle::STATUS_PUBLISHED;
        $this->assertTrue($article->saveWithTags());
        $this->assertTrue($this->visible($article));
        $this->assertNotNull($article->published_at);
        $publishedAt = $article->published_at;
        $article->title = '修改已发布标题';
        $this->assertTrue($article->saveWithTags());
        $this->assertSame($publishedAt, $article->published_at);

        $this->category->status = NewsCategory::STATUS_DISABLED;
        $this->assertTrue($this->category->save());
        $this->assertFalse($this->visible($article));
        $this->assertFalse($article->saveWithTags());
        $this->assertTrue($article->hasErrors('category_id'));
        $this->category->status = NewsCategory::STATUS_ENABLED;
        $this->assertTrue($this->category->save());
        $article->status = NewsArticle::STATUS_WITHDRAWN;
        $this->assertTrue($article->saveWithTags());
        $this->assertFalse($this->visible($article));
        $article->status = NewsArticle::STATUS_PUBLISHED;
        $this->assertTrue($article->saveWithTags());
        $this->assertTrue($this->visible($article));
    }

    public function testSynchronizesMultipleTagsAndClearsAllSelections()
    {
        $first = $this->tag();
        $second = $this->tag();
        $article = $this->article();
        $article->tagIds = [$first->id, $second->id, $first->id];
        $this->assertTrue($article->saveWithTags(), json_encode($article->errors));
        $this->assertCount(2, $article->tags);
        $article->tagIds = [$second->id];
        $this->assertTrue($article->saveWithTags());
        $this->assertCount(1, $article->tags);
        $this->assertEquals($second->id, $article->tags[0]->id);
        $article->tagIds = '';
        $this->assertTrue($article->saveWithTags());
        $this->assertCount(0, $article->tags);
    }

    public function testInvalidTagsDoNotPartiallySaveArticle()
    {
        $tag = $this->tag();
        $article = $this->article();
        $article->tagIds = [$tag->id];
        $this->assertTrue($article->saveWithTags());
        $originalTitle = $article->title;
        $article->title = '不应保存的修改';
        $article->tagIds = ['999999999999'];
        $this->assertFalse($article->saveWithTags());
        $this->assertSame($originalTitle, NewsArticle::findOne($article->id)->title);
        $this->assertEquals(1, NewsArticleTag::find()->where(['article_id' => $article->id])->count());
        $article->tagIds = [['nested']];
        $this->assertFalse($article->validate());
        $this->assertTrue($article->hasErrors('tagIds'));
    }

    public function testDeletesOnlyTheExpectedAssociations()
    {
        $tag = $this->tag();
        $article = $this->article();
        $article->tagIds = [$tag->id];
        $this->assertTrue($article->saveWithTags());
        $tag->delete();
        $this->assertNotNull(NewsArticle::findOne($article->id));
        $this->assertFalse(NewsArticleTag::find()->where(['article_id' => $article->id])->exists());
        $tag = $this->tag();
        $article->tagIds = [$tag->id];
        $this->assertTrue($article->saveWithTags());
        $id = $article->id;
        $article->delete();
        $this->assertFalse(NewsArticleTag::find()->where(['article_id' => $id])->exists());
        $this->assertNotNull(NewsTag::findOne($tag->id));
    }

    public function testCombinesPublicSearchAndTagWithoutLeakingDrafts()
    {
        $tag = $this->tag();
        $article = $this->article();
        $article->title = '组合筛选测试-' . bin2hex(random_bytes(4));
        $article->tagIds = [$tag->id];
        $article->status = NewsArticle::STATUS_PUBLISHED;
        $this->assertTrue($article->saveWithTags());
        $draft = $this->article();
        $draft->title = $article->title;
        $draft->tagIds = [$tag->id];
        $this->assertTrue($draft->saveWithTags());
        $search = new NewsSearch(['scenario' => 'public']);
        $search->load(['q' => $article->title, 'category_id' => $this->category->id, 'tag_id' => $tag->id, 'status' => 0], '');
        $this->assertTrue($search->validate());
        $this->assertEquals([$article->id], $search->query()->select('article.id')->column());
    }

    public function testRejectsUnsafeSourcesAndMalformedFilters()
    {
        $article = $this->article();
        $article->source_url = 'javascript:alert(1)';
        $this->assertFalse($article->validate());
        $this->assertTrue($article->hasErrors('source_url'));
        $search = new NewsSearch(['scenario' => 'public', 'category_id' => ['1']]);
        $this->assertFalse($search->validate());
        $this->assertFalse($article->isAttributeSafe('published_at'));
    }

    private function article()
    {
        return new NewsArticleForm(['category_id' => $this->category->id, 'title' => '测试新闻', 'body' => "测试正文\n第二行"]);
    }

    private function tag()
    {
        $tag = new NewsTag(['name' => '测试标签', 'slug' => 'test-' . bin2hex(random_bytes(6))]);
        $this->assertTrue($tag->save());
        return $tag;
    }

    private function visible($article)
    {
        return NewsArticle::publishedQuery()->andWhere(['article.id' => $article->id])->exists();
    }
}

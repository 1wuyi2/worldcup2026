<?php

/**
 * 模块 B 后台文章查询、编辑、标签保存及发布生命周期。
 * @author XPC
 * @date 2026-11-22
 */

namespace backend\controllers;

use backend\models\NewsArticleForm;
use common\models\NewsArticle;
use common\models\NewsSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\IntegrityException;
use yii\db\StaleObjectException;
use yii\web\BadRequestHttpException;

class NewsController extends NewsAdminController
{
    public function actionIndex()
    {
        $search = new NewsSearch();
        $search->load(Yii::$app->request->queryParams, '');
        if (!$search->validate()) {
            throw new BadRequestHttpException('新闻筛选参数无效。');
        }
        return $this->render('index', [
            'search' => $search,
            'provider' => new ActiveDataProvider([
                'query' => $search->query(false),
                'sort' => ['defaultOrder' => ['updated_at' => SORT_DESC, 'id' => SORT_DESC]],
                'pagination' => ['pageSize' => 20],
            ]),
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', ['article' => $this->findRecord(NewsArticle::class, $id)]);
    }

    public function actionCreate()
    {
        return $this->edit(new NewsArticleForm());
    }

    public function actionUpdate($id)
    {
        $model = $this->findRecord(NewsArticleForm::class, $id);
        $model->loadTags();
        return $this->edit($model);
    }

    public function actionDelete($id)
    {
        $model = $this->findRecord(NewsArticle::class, $id);
        $model->delete();
        Yii::$app->session->setFlash('success', '文章及其标签关联已删除。');
        return $this->redirect(['index']);
    }

    public function actionPublish($id)
    {
        return $this->changeStatus($id, NewsArticle::STATUS_PUBLISHED);
    }

    public function actionWithdraw($id)
    {
        return $this->changeStatus($id, NewsArticle::STATUS_WITHDRAWN);
    }

    private function changeStatus($id, $status)
    {
        $article = $this->findRecord(NewsArticle::class, $id);
        $article->status = $status;
        try {
            if ($article->save()) {
                Yii::$app->session->setFlash('success', $status === NewsArticle::STATUS_PUBLISHED ? '新闻已发布。' : '新闻已撤稿。');
            } else {
                Yii::$app->session->setFlash('error', implode('；', $article->getFirstErrors()));
            }
        } catch (IntegrityException $e) {
            Yii::$app->session->setFlash('error', '关联数据发生变化，请重新编辑后发布。');
        }
        return $this->redirect(['view', 'id' => $article->id]);
    }

    private function edit(NewsArticleForm $model)
    {
        if ($model->load(Yii::$app->request->post())) {
            try {
                if ($model->saveWithTags()) {
                    Yii::$app->session->setFlash('success', '文章及标签已保存。');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } catch (IntegrityException $e) {
                $model->addError('tagIds', '分类或标签发生变化，保存未完成。请刷新后重新选择。');
            } catch (StaleObjectException $e) {
                throw new BadRequestHttpException('文章已被删除，请返回列表。');
            }
        }
        return $this->render('edit', ['model' => $model]);
    }
}

<?php

/**
 * 模块 B 分类新增、编辑、停用和受引用保护的删除。
 * @author XPC
 * @date 2026-11-22
 */

namespace backend\controllers;

use common\models\NewsCategory;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\IntegrityException;

class NewsCategoryController extends NewsAdminController
{
    public function actionIndex()
    {
        return $this->render('/news-taxonomy/index', [
            'title' => '新闻分类管理',
            'provider' => new ActiveDataProvider([
                'query' => NewsCategory::find(),
                'sort' => ['defaultOrder' => ['sort_order' => SORT_ASC, 'id' => SORT_ASC]],
                'pagination' => ['pageSize' => 20],
            ]),
            'isCategory' => true,
        ]);
    }

    public function actionCreate()
    {
        return $this->edit(new NewsCategory());
    }

    public function actionUpdate($id)
    {
        return $this->edit($this->findRecord(NewsCategory::class, $id));
    }

    public function actionDelete($id)
    {
        $model = $this->findRecord(NewsCategory::class, $id);
        try {
            if ($model->getArticles()->exists()) {
                Yii::$app->session->setFlash('error', '该分类仍有文章，请先调整文章分类，或将分类停用。');
            } else {
                $model->delete();
                Yii::$app->session->setFlash('success', '分类已删除。');
            }
        } catch (IntegrityException $e) {
            Yii::$app->session->setFlash('error', '分类正在被文章引用，无法删除。');
        }
        return $this->redirect(['index']);
    }

    private function edit(NewsCategory $model)
    {
        if ($model->load(Yii::$app->request->post())) {
            try {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', '分类已保存。');
                    return $this->redirect(['index']);
                }
            } catch (IntegrityException $e) {
                $model->addError('name', '分类名称已存在，请修改后重试。');
            }
        }
        return $this->render('/news-taxonomy/edit', [
            'model' => $model, 'isCategory' => true,
            'title' => $model->isNewRecord ? '新增新闻分类' : '编辑新闻分类',
        ]);
    }
}

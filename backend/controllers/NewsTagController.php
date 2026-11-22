<?php

/**
 * 模块 B 标签维护，删除标签仅移除关联而不删除文章。
 * @author XPC
 * @date 2026-11-22
 */

namespace backend\controllers;

use common\models\NewsTag;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\IntegrityException;

class NewsTagController extends NewsAdminController
{
    public function actionIndex()
    {
        return $this->render('/news-taxonomy/index', [
            'title' => '新闻标签管理',
            'provider' => new ActiveDataProvider([
                'query' => NewsTag::find(),
                'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
                'pagination' => ['pageSize' => 20],
            ]),
            'isCategory' => false,
        ]);
    }

    public function actionCreate()
    {
        return $this->edit(new NewsTag());
    }

    public function actionUpdate($id)
    {
        return $this->edit($this->findRecord(NewsTag::class, $id));
    }

    public function actionDelete($id)
    {
        $model = $this->findRecord(NewsTag::class, $id);
        $model->delete();
        Yii::$app->session->setFlash('success', '标签及其文章关联已删除，文章内容保留。');
        return $this->redirect(['index']);
    }

    private function edit(NewsTag $model)
    {
        if ($model->load(Yii::$app->request->post())) {
            try {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', '标签已保存。');
                    return $this->redirect(['index']);
                }
            } catch (IntegrityException $e) {
                $model->addError('slug', '标签标识已存在，请修改后重试。');
            }
        }
        return $this->render('/news-taxonomy/edit', [
            'model' => $model, 'isCategory' => false,
            'title' => $model->isNewRecord ? '新增新闻标签' : '编辑新闻标签',
        ]);
    }
}

<?php

/**
 * 模块 B 公开新闻列表、筛选和详情，统一限制发布及分类状态。
 * @author XPC
 * @date 2026-11-22
 */

namespace frontend\controllers;

use common\models\NewsArticle;
use common\models\NewsCategory;
use common\models\NewsSearch;
use common\models\NewsTag;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class NewsController extends Controller
{
    public function actionIndex()
    {
        $search = new NewsSearch(['scenario' => 'public']);
        $search->load(Yii::$app->request->queryParams, '');
        if (!$search->validate()) {
            throw new BadRequestHttpException('新闻筛选参数无效。');
        }
        if ($search->category_id !== null && !NewsCategory::find()->where([
            'id' => $search->category_id, 'status' => NewsCategory::STATUS_ENABLED,
        ])->exists()) {
            throw new NotFoundHttpException('新闻分类不存在或已停用。');
        }
        if ($search->tag_id !== null && !NewsTag::find()->where(['id' => $search->tag_id])->exists()) {
            throw new NotFoundHttpException('新闻标签不存在。');
        }
        return $this->render('index', [
            'search' => $search,
            'provider' => new ActiveDataProvider([
                'query' => $search->query(),
                'sort' => [
                    'attributes' => ['published_at', 'id'],
                    'defaultOrder' => ['published_at' => SORT_DESC, 'id' => SORT_DESC],
                ],
                'pagination' => ['pageSize' => 10],
            ]),
        ]);
    }

    public function actionView($id)
    {
        if (!is_scalar($id) || !preg_match('/^[1-9][0-9]{0,19}$/D', (string) $id)) {
            throw new NotFoundHttpException('新闻不存在或尚未发布。');
        }
        $article = NewsArticle::publishedQuery()->with(['category', 'tags'])->andWhere(['article.id' => $id])->one();
        if ($article === null) {
            throw new NotFoundHttpException('新闻不存在或尚未发布。');
        }
        return $this->render('view', ['article' => $article]);
    }
}

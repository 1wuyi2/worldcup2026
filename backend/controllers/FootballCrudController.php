<?php

/**
 * 模块 C 后台通用列表、表单和访问控制。
 * @author lzs
 * @date 2026-11-20
 */

namespace backend\controllers;

use common\models\FootballRecord;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\IntegrityException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

abstract class FootballCrudController extends Controller
{
    protected $modelClass;
    protected $sectionTitle;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'roles' => ['manageFootball']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => ['delete' => ['POST']],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('/football-admin/index', [
            'sectionTitle' => $this->sectionTitle,
            'provider' => new ActiveDataProvider([
                'query' => $this->query(),
                'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
                'pagination' => ['pageSize' => 20],
            ]),
            'columns' => $this->columns(),
        ]);
    }

    public function actionCreate()
    {
        $class = $this->modelClass;
        return $this->edit(new $class());
    }

    public function actionUpdate($id)
    {
        return $this->edit($this->findModel($id));
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        try {
            if ($model->delete() === 1) {
                Yii::$app->session->setFlash('success', '记录已删除。');
            } else {
                Yii::$app->session->setFlash('warning', '记录已发生变化，请刷新列表。');
            }
        } catch (IntegrityException $exception) {
            Yii::$app->session->setFlash('error', '此记录仍被其他数据引用。请先处理关联记录。');
        }

        return $this->redirect(['index']);
    }

    protected function query(): ActiveQuery
    {
        $class = $this->modelClass;
        return $class::find();
    }

    abstract protected function columns(): array;

    abstract protected function fields(): array;

    private function edit(FootballRecord $model)
    {
        if ($model->load(Yii::$app->request->post())) {
            try {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', '记录已保存。');
                    return $this->redirect(['index']);
                }
            } catch (IntegrityException $exception) {
                Yii::$app->session->setFlash('error', '关联记录或唯一字段已发生变化，请检查后重试。');
            }
        }

        return $this->render('/football-admin/edit', [
            'sectionTitle' => $this->sectionTitle,
            'model' => $model,
            'fields' => $this->fields(),
        ]);
    }

    private function findModel($id): FootballRecord
    {
        if (!is_scalar($id)) {
            throw new NotFoundHttpException('未找到该记录。');
        }

        $class = $this->modelClass;
        $model = $class::findOne(['id' => $id]);
        if ($model === null) {
            throw new NotFoundHttpException('未找到该记录。');
        }

        return $model;
    }
}

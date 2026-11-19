<?php

/**
 * 模块 A 团队资料后台管理控制器。
 *
 * @author wuyi
 * @date 2026-11-19
 */

namespace backend\controllers;

use common\models\PortalTeam;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PortalTeamController extends Controller
{
    const PERMISSION_MANAGE_PORTAL = 'managePortal';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return Yii::$app->user->can(self::PERMISSION_MANAGE_PORTAL);
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = PortalTeam::find()->orderBy(['id' => SORT_ASC])->one();

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $existingModel = PortalTeam::find()->orderBy(['id' => SORT_ASC])->one();
        if ($existingModel !== null) {
            Yii::$app->session->setFlash('warning', '团队资料已经存在，请直接修改现有记录。');
            return $this->redirect(['update', 'id' => $existingModel->id]);
        }

        $model = new PortalTeam();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '团队资料创建成功。');
                return $this->redirect(['view', 'id' => $model->id]);
            }

            Yii::$app->session->setFlash('error', '团队资料保存失败，请检查表单内容。');
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '团队资料更新成功。');
                return $this->redirect(['view', 'id' => $model->id]);
            }

            Yii::$app->session->setFlash('error', '团队资料保存失败，请检查表单内容。');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        $model = PortalTeam::findOne($id);
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('未找到指定的团队资料。');
    }
}

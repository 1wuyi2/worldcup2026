<?php

/**
 * 模块 A 团队成员后台管理控制器。
 *
 * @author wuyi
 * @date 2026-11-19
 */

namespace backend\controllers;

use common\models\PortalMember;
use common\models\PortalTeam;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PortalMemberController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => PortalMember::find()
                ->with('team')
                ->orderBy([
                    'team_id' => SORT_ASC,
                    'sort_order' => SORT_ASC,
                    'id' => SORT_ASC,
                ]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
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
        $team = PortalTeam::find()->orderBy(['id' => SORT_ASC])->one();
        if ($team === null) {
            Yii::$app->session->setFlash('warning', '请先创建团队资料，再添加团队成员。');
            return $this->redirect(['/portal-team/create']);
        }

        $model = new PortalMember();
        $model->team_id = $team->id;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '成员资料创建成功。');
                return $this->redirect(['view', 'id' => $model->id]);
            }

            Yii::$app->session->setFlash('error', '成员资料保存失败，请检查表单内容。');
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
                Yii::$app->session->setFlash('success', '成员资料更新成功。');
                return $this->redirect(['view', 'id' => $model->id]);
            }

            Yii::$app->session->setFlash('error', '成员资料保存失败，请检查表单内容。');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->delete() !== false) {
            Yii::$app->session->setFlash('success', '成员资料已删除。');
        } else {
            Yii::$app->session->setFlash('error', '成员资料删除失败。');
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        $model = PortalMember::findOne($id);
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('未找到指定的成员资料。');
    }
}

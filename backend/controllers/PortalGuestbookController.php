<?php

/**
 * 模块 A 留言审核与回复后台控制器。
 *
 * @author wuyi
 * @date 2026-11-20
 */

namespace backend\controllers;

use common\models\PortalMessage;
use common\models\PortalReply;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\IntegrityException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PortalGuestbookController extends Controller
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
                    'publish' => ['post'],
                    'hide' => ['post'],
                    'reply' => ['get', 'post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $query = PortalMessage::find()
            ->with('reply')
            ->orderBy(['created_at' => SORT_DESC, 'id' => SORT_DESC]);

        $status = Yii::$app->request->get('status', '');
        $allowedStatuses = array_map('strval', array_keys(PortalMessage::getStatusOptions()));
        if ($status !== '' && in_array((string) $status, $allowedStatuses, true)) {
            $query->andWhere(['status' => (int) $status]);
        } else {
            $status = '';
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'status' => $status,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionPublish($id)
    {
        return $this->changeStatus($id, PortalMessage::STATUS_PUBLISHED, '留言已审核发布。');
    }

    public function actionHide($id)
    {
        return $this->changeStatus($id, PortalMessage::STATUS_HIDDEN, '留言已隐藏。');
    }

    public function actionReply($id)
    {
        $message = $this->findModel($id);
        $reply = PortalReply::findOne(['message_id' => $message->id]);
        $isNewReply = $reply === null;

        if ($isNewReply) {
            $reply = new PortalReply();
            $reply->message_id = $message->id;
        }

        if ($reply->load(Yii::$app->request->post())) {
            $reply->message_id = $message->id;
            $reply->admin_user_id = Yii::$app->user->id;

            try {
                if ($reply->save()) {
                    Yii::$app->session->setFlash(
                        'success',
                        $isNewReply ? '管理员回复创建成功。' : '管理员回复更新成功。'
                    );
                    return $this->redirect(['view', 'id' => $message->id]);
                }

                Yii::$app->session->setFlash('error', '回复保存失败，请检查回复内容。');
            } catch (IntegrityException $exception) {
                $reply->addError('content', '该留言已经存在回复，请刷新页面后修改现有回复。');
                Yii::$app->session->setFlash('error', '回复保存失败，请勿重复创建回复。');
            } catch (\Throwable $exception) {
                Yii::error('Portal reply save failed: ' . get_class($exception), __METHOD__);
                Yii::$app->session->setFlash('error', '回复保存失败，请稍后重试。');
            }
        }

        return $this->render('reply', [
            'message' => $message,
            'reply' => $reply,
            'isNewReply' => $isNewReply,
        ]);
    }

    protected function findModel($id)
    {
        $model = PortalMessage::findOne($id);
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('未找到指定的留言。');
    }

    private function changeStatus($id, $status, $successMessage)
    {
        $model = $this->findModel($id);
        $model->status = $status;

        try {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', $successMessage);
            } else {
                Yii::$app->session->setFlash('error', '留言状态更新失败。');
            }
        } catch (\Throwable $exception) {
            Yii::error('Portal message status update failed: ' . get_class($exception), __METHOD__);
            Yii::$app->session->setFlash('error', '留言状态更新失败。');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }
}

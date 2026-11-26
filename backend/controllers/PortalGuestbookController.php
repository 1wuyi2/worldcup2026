<?php

/**
 * 模块 A 留言审核与回复后台控制器。
 *
 * @author wuyi
 * @date 2026-11-20
 */

namespace backend\controllers;

use backend\models\PortalMessageForm;
use common\models\PortalMember;
use common\models\PortalMessage;
use common\models\PortalReply;
use common\models\PortalTeam;
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
    'index' => ['get'],
    'view' => ['get'],
    'create' => ['get', 'post'],
    'update' => ['get', 'post'],
    'publish' => ['post'],
    'hide' => ['post'],
    'delete' => ['post'],
    'reply' => ['get', 'post'],
],
            ],
        ];
    }

   public function actionIndex()
{
    $query = PortalMessage::find()
        ->with(['member', 'reply'])
        ->orderBy([
            'created_at' => SORT_DESC,
            'id' => SORT_DESC,
        ]);

    // 1. 校验审核状态。
    $status = Yii::$app->request->get('status', '');

    $allowedStatuses = array_map(
        'strval',
        array_keys(PortalMessage::getStatusOptions())
    );

    if (
        (!is_string($status) && !is_int($status))
        || !in_array((string) $status, $allowedStatuses, true)
    ) {
        $status = '';
    } else {
        $status = (string) $status;
    }

    // 2. 校验留言发布身份。
    $identity = Yii::$app->request->get('identity', '');

    if (
        !is_string($identity)
        || !in_array(
            $identity,
            ['', 'team', 'member', 'visitor'],
            true
        )
    ) {
        $identity = '';
    }

    // 3. 校验成员编号。
    $memberId = Yii::$app->request->get('member_id', '');

    $selectedMember = null;

    if (
        (is_string($memberId) || is_int($memberId))
        && ctype_digit((string) $memberId)
        && (int) $memberId > 0
    ) {
        $selectedMember = PortalMember::findOne([
            'id' => (string) $memberId,
        ]);
    }

    if ($selectedMember !== null) {
        $memberId = $selectedMember->id;

        // 指定成员时，以成员身份为准。
        $identity = 'member';
    } else {
        $memberId = '';
    }

    // 4. 根据统一后的条件构造查询。
    if ($status !== '') {
        $query->andWhere([
            'status' => (int) $status,
        ]);
    }

    if ($identity === 'team') {
        $query->andWhere([
            'source_type' => PortalMessage::SOURCE_ADMIN,
            'member_id' => null,
        ]);
    } elseif ($identity === 'member') {
        $query->andWhere([
            'source_type' => PortalMessage::SOURCE_ADMIN,
        ]);

        $query->andWhere([
            'not',
            ['member_id' => null],
        ]);
    } elseif ($identity === 'visitor') {
        $query->andWhere([
            'source_type' => PortalMessage::SOURCE_VISITOR,
        ]);
    }

    if ($memberId !== '') {
        $query->andWhere([
            'member_id' => $memberId,
        ]);
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
        'identity' => $identity,
        'memberId' => $memberId,
        'memberOptions' => $this->getMemberOptions(),
    ]);
}

    public function actionCreate()
    {
        $team = $this->findTeam();
        if ($team === null) {
            Yii::$app->session->setFlash('warning', '请先创建真实团队资料，再以团队或成员身份发布留言。');
            return $this->redirect(['/portal-team/create']);
        }

        $message = new PortalMessage();
        $form = new PortalMessageForm();
        if ($this->loadAndSave($form, $message, $team)) {
            Yii::$app->session->setFlash('success', '留言创建成功。');
            return $this->redirect(['view', 'id' => $message->id]);
        }

        return $this->render('create', [
            'model' => $form,
            'memberOptions' => $this->getMemberOptions(),
        ]);
    }

    public function actionUpdate($id)
    {
        $message = $this->findModel($id);
        if (!$message->isAdminMessage()) {
            Yii::$app->session->setFlash('error', '历史访客留言正文和来源不能由管理员修改。');
            return $this->redirect(['view', 'id' => $message->id]);
        }

        $team = $this->findTeam();
        if ($team === null) {
            Yii::$app->session->setFlash('warning', '缺少团队资料，暂时不能修改后台发布留言。');
            return $this->redirect(['view', 'id' => $message->id]);
        }

        $form = new PortalMessageForm($message);
        if ($this->loadAndSave($form, $message, $team)) {
            Yii::$app->session->setFlash('success', '留言修改成功。');
            return $this->redirect(['view', 'id' => $message->id]);
        }

        return $this->render('update', [
            'model' => $form,
            'message' => $message,
            'memberOptions' => $this->getMemberOptions(),
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
        if (!$message->isVisitorMessage()) {
            Yii::$app->session->setFlash('error', '后台主动发布的留言不使用管理员回复功能。');
            return $this->redirect(['view', 'id' => $message->id]);
        }
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

    public function actionDelete($id)
    {
        $message = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $reply = PortalReply::findOne(['message_id' => $message->id]);
            if ($reply !== null && $reply->delete() === false) {
                throw new \RuntimeException('Reply delete failed.');
            }
            if ($message->delete() === false) {
                throw new \RuntimeException('Message delete failed.');
            }

            $transaction->commit();
            Yii::$app->session->setFlash('success', '留言记录已删除。');
            return $this->redirect(['index']);
        } catch (\Throwable $exception) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }
            Yii::error('Portal message delete failed: ' . get_class($exception), __METHOD__);
            Yii::$app->session->setFlash('error', '留言删除失败，关联回复和原记录均已保留。');
            return $this->redirect(['view', 'id' => $message->id]);
        }
    }

   protected function findModel($id)
{
    if (
        (!is_string($id) && !is_int($id))
        || !ctype_digit((string) $id)
        || (int) $id < 1
    ) {
        throw new \yii\web\BadRequestHttpException(
            '无效的留言编号。'
        );
    }

    $model = PortalMessage::findOne([
        'id' => (string) $id,
    ]);

    if ($model !== null) {
        return $model;
    }

    throw new NotFoundHttpException(
        '未找到指定的留言。'
    );
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

    private function loadAndSave(PortalMessageForm $form, PortalMessage $message, PortalTeam $team)
    {
        if (!$form->load(Yii::$app->request->post())) {
            return false;
        }

        if ($form->publisher_type === PortalMessageForm::PUBLISHER_TEAM) {
            $form->member_id = null;
        }
        if (!$form->validate()) {
            Yii::$app->session->setFlash('error', '留言保存失败，请检查表单内容。');
            return false;
        }

        $member = null;
        if ($form->publisher_type === PortalMessageForm::PUBLISHER_MEMBER) {
            $member = PortalMember::findOne($form->member_id);
            if ($member === null) {
                $form->addError('member_id', '所选发布成员不存在。');
                return false;
            }
        }

        $message->setScenario($message->isNewRecord
            ? PortalMessage::SCENARIO_ADMIN_CREATE
            : PortalMessage::SCENARIO_ADMIN_UPDATE);
        $message->source_type = PortalMessage::SOURCE_ADMIN;
        $message->member_id = $member === null ? null : $member->id;
        $message->display_name = $member === null ? $team->name : $member->name;
        $message->content = $form->content;
        $message->status = $form->status;
        if ($message->isNewRecord) {
            $message->created_by_user_id = Yii::$app->user->id;
        }

        try {
            if ($message->save()) {
                return true;
            }
            $form->addErrors($message->getErrors());
        } catch (\Throwable $exception) {
            Yii::error('Portal admin message save failed: ' . get_class($exception), __METHOD__);
            $form->addError('content', '留言保存失败，请稍后重试。');
        }

        Yii::$app->session->setFlash('error', '留言保存失败，请检查表单内容。');
        return false;
    }

    private function findTeam()
    {
        return PortalTeam::find()->orderBy(['id' => SORT_ASC])->one();
    }

    private function getMemberOptions()
    {
        $options = [];
        foreach (PortalMember::find()->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC])->all() as $member) {
            $options[$member->id] = $member->name;
        }

        return $options;
    }
}

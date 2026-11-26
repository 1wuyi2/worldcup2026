<?php

/**
 * 模块 A 课程资料后台管理与安全上传控制器。
 *
 * @author wuyi
 * @date 2026-11-20
 */

namespace backend\controllers;

use backend\models\PortalAssignmentForm;
use common\components\PortalAssignmentFile;
use common\models\PortalAssignment;
use common\models\PortalMember;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class PortalAssignmentController extends Controller
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
    'unpublish' => ['post'],
    'delete' => ['post'],
],
            ],
        ];
    }

   public function actionIndex()
{
    $query = PortalAssignment::find()
        ->with('member')
        ->orderBy([
            'created_at' => SORT_DESC,
            'id' => SORT_DESC,
        ]);

    // 1. 读取并校验作业分类。
    $category = Yii::$app->request->get('category', '');

    if (
        !is_string($category)
        || !array_key_exists(
            $category,
            PortalAssignment::getCategoryOptions()
        )
    ) {
        $category = '';
    }

    // 2. 读取并校验发布状态。
    $status = Yii::$app->request->get('status', '');

    $allowedStatuses = array_map(
        'strval',
        array_keys(PortalAssignment::getStatusOptions())
    );

    if (
        (!is_string($status) && !is_int($status))
        || !in_array((string) $status, $allowedStatuses, true)
    ) {
        $status = '';
    } else {
        $status = (string) $status;
    }

    // 3. 读取并校验成员编号。
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

        // 指定具体成员时，统一筛选个人作业。
        $category = PortalAssignment::CATEGORY_PERSONAL;
    } else {
        $memberId = '';
    }

    // 4. 所有筛选条件校验完成后，再构造数据库查询。
    if ($category !== '') {
        $query->andWhere([
            'category' => $category,
        ]);
    }

    if ($status !== '') {
        $query->andWhere([
            'status' => (int) $status,
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
        'category' => $category,
        'status' => $status,
        'memberId' => $memberId,
        'memberOptions' => $this->getMemberOptions(),
    ]);
}
    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    public function actionCreate()
    {
        $model = new PortalAssignment();
        $form = new PortalAssignmentForm();

        if ($this->loadAndSave($form, $model)) {
            Yii::$app->session->setFlash('success', '课程资料创建成功。');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $form,
            'memberOptions' => $this->getMemberOptions(),
        ]);
    }

    public function actionUpdate($id)
    {
        $assignment = $this->findModel($id);
        $form = new PortalAssignmentForm($assignment);

        if ($this->loadAndSave($form, $assignment)) {
            Yii::$app->session->setFlash('success', '课程资料更新成功。');
            return $this->redirect(['view', 'id' => $assignment->id]);
        }

        return $this->render('update', [
            'model' => $form,
            'assignment' => $assignment,
            'memberOptions' => $this->getMemberOptions(),
        ]);
    }

    public function actionPublish($id)
    {
        $model = $this->findModel($id);
        if ($model->category === PortalAssignment::CATEGORY_PERSONAL && $model->member_id === null) {
            Yii::$app->session->setFlash('error', '发布失败：历史个人作业必须先编辑并补充所属成员。');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        if (PortalAssignmentFile::resolve($model) === null) {
            Yii::$app->session->setFlash('error', '发布失败：文件不存在、路径不合法或文件不在允许目录内。');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->status = PortalAssignment::STATUS_PUBLISHED;
        try {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '课程资料已发布。');
                return $this->redirect(['view', 'id' => $model->id]);
            }
            Yii::$app->session->setFlash('error', '课程资料发布失败，请检查资料记录。');
        } catch (\Throwable $exception) {
            Yii::error('Portal assignment publish failed: ' . get_class($exception), __METHOD__);
            Yii::$app->session->setFlash('error', '课程资料发布失败，请稍后重试。');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionUnpublish($id)
    {
        $model = $this->findModel($id);
        try {
            $updatedRows = $model->updateAttributes([
                'status' => PortalAssignment::STATUS_UNPUBLISHED,
                'updated_at' => time(),
            ]);
            if ($updatedRows > 0 || (int) $model->status === PortalAssignment::STATUS_UNPUBLISHED) {
                Yii::$app->session->setFlash('success', '课程资料已取消发布。');
                return $this->redirect(['view', 'id' => $model->id]);
            }
            Yii::$app->session->setFlash('error', '取消发布失败。');
        } catch (\Throwable $exception) {
            Yii::error('Portal assignment unpublish failed: ' . get_class($exception), __METHOD__);
            Yii::$app->session->setFlash('error', '取消发布失败，请稍后重试。');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        try {
            if ($model->delete() !== false) {
                Yii::$app->session->setFlash('success', '课程资料记录已删除，磁盘文件未删除。');
                return $this->redirect(['index']);
            }
            Yii::$app->session->setFlash('error', '课程资料记录删除失败。');
        } catch (\Throwable $exception) {
            Yii::error('Portal assignment delete failed: ' . get_class($exception), __METHOD__);
            Yii::$app->session->setFlash('error', '课程资料记录删除失败，请稍后重试。');
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id)
{
    if (
        (!is_string($id) && !is_int($id))
        || !ctype_digit((string) $id)
        || (int) $id < 1
    ) {
        throw new \yii\web\BadRequestHttpException(
            '无效的课程资料编号。'
        );
    }

    $model = PortalAssignment::findOne([
        'id' => (string) $id,
    ]);

    if ($model !== null) {
        return $model;
    }

    throw new NotFoundHttpException(
        '未找到指定的课程资料。'
    );
}

    private function loadAndSave(PortalAssignmentForm $form, PortalAssignment $assignment)
    {
        if (!$form->load(Yii::$app->request->post())) {
            return false;
        }

        if ($form->category === PortalAssignment::CATEGORY_TEAM) {
            $form->member_id = null;
        }

        $form->uploadFile = UploadedFile::getInstance($form, 'uploadFile');
        if ($form->uploadFile !== null) {
            $form->relative_path = null;
        }
        if (!$form->validate()) {
            Yii::$app->session->setFlash('error', '资料保存失败，请检查表单内容。');
            return false;
        }

        $newFilePath = null;
        if ($form->uploadFile !== null) {
            $newFilePath = $this->saveUploadedFile($form);
            if ($newFilePath === null) {
                return false;
            }
        }

        $member = null;
        if ($form->category === PortalAssignment::CATEGORY_PERSONAL) {
            $member = PortalMember::findOne($form->member_id);
            if ($member === null) {
                $form->addError('member_id', '所选团队成员不存在。');
                $this->removeNewUpload($newFilePath);
                return false;
            }
        }

        $assignment->title = $form->title;
        $assignment->category = $form->category;
        $assignment->member_id = $member === null ? null : $member->id;
        $assignment->owner_label = $member === null ? $form->owner_label : $member->name;
        $assignment->relative_path = $newFilePath !== null ? $newFilePath : $form->relative_path;
        $assignment->description = $form->description;
        $assignment->status = $form->status;

        if ($assignment->isPublished() && PortalAssignmentFile::resolve($assignment) === null) {
            $form->addError('relative_path', '已发布资料必须对应允许目录内实际存在的文件。');
            $this->removeNewUpload($newFilePath);
            Yii::$app->session->setFlash('error', '资料未保存：发布前必须确认文件存在且路径安全。');
            return false;
        }

        try {
            if ($assignment->save()) {
                return true;
            }
            $form->addErrors($assignment->getErrors());
        } catch (\Throwable $exception) {
            Yii::error('Portal assignment save failed: ' . get_class($exception), __METHOD__);
            $form->addError('relative_path', '资料保存失败，请稍后重试。');
        }

        $this->removeNewUpload($newFilePath);
        Yii::$app->session->setFlash('error', '资料保存失败，请检查表单内容。');
        return false;
    }

    private function saveUploadedFile(PortalAssignmentForm $form)
    {
        $categoryDirectory = $form->category === PortalAssignment::CATEGORY_TEAM ? 'team' : 'personal';
        $projectRoot = dirname(Yii::getAlias('@common'));
        $targetDirectory = $projectRoot . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $categoryDirectory;

        if (!is_dir($targetDirectory) || !is_writable($targetDirectory)) {
            $form->addError('uploadFile', '资料目录不存在或不可写，请检查部署目录权限。');
            Yii::$app->session->setFlash('error', '文件上传失败：资料目录不可用。');
            return null;
        }

        $extension = strtolower($form->uploadFile->extension);
        $fileName = date('YmdHis') . '-' . Yii::$app->security->generateRandomString(16) . '.' . $extension;
        $absolutePath = $targetDirectory . DIRECTORY_SEPARATOR . $fileName;
        $relativePath = 'data/' . $categoryDirectory . '/' . $fileName;
        if (!$form->uploadFile->saveAs($absolutePath, false)) {
            $this->removeNewUpload($relativePath);
            $form->addError('uploadFile', '文件保存失败，请稍后重试。');
            Yii::$app->session->setFlash('error', '文件上传失败。');
            return null;
        }

        return $relativePath;
    }

    private function removeNewUpload($relativePath)
    {
        if ($relativePath === null) {
            return;
        }

        $projectRoot = dirname(Yii::getAlias('@common'));
        $absolutePath = $projectRoot . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        if (is_file($absolutePath)) {
            $pathFingerprint = hash('sha256', $relativePath);
            try {
                if (!@unlink($absolutePath)) {
                    $this->logCleanupWarning(
                        'New assignment upload cleanup failed. Path fingerprint: ' . $pathFingerprint,
                    );
                }
            } catch (\Throwable $exception) {
                $this->logCleanupWarning(
                    'New assignment upload cleanup raised ' . get_class($exception)
                    . '. Path fingerprint: ' . $pathFingerprint,
                );
            }
        }
    }

    private function logCleanupWarning($message)
    {
        try {
            Yii::warning($message, __METHOD__);
        } catch (\Throwable $ignoredException) {
            // Cleanup logging must not mask the original save failure.
        }
    }

    private function getMemberOptions()
    {
        $options = [];
        $members = PortalMember::find()
            ->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC])
            ->all();
        foreach ($members as $member) {
            $options[$member->id] = $member->name;
        }

        return $options;
    }
}

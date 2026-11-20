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
                    'publish' => ['post'],
                    'unpublish' => ['post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $query = PortalAssignment::find()->orderBy(['created_at' => SORT_DESC, 'id' => SORT_DESC]);

        $category = Yii::$app->request->get('category', '');
        if (array_key_exists($category, PortalAssignment::getCategoryOptions())) {
            $query->andWhere(['category' => $category]);
        } else {
            $category = '';
        }

        $status = Yii::$app->request->get('status', '');
        $allowedStatuses = array_map('strval', array_keys(PortalAssignment::getStatusOptions()));
        if ($status !== '' && in_array((string) $status, $allowedStatuses, true)) {
            $query->andWhere(['status' => (int) $status]);
        } else {
            $status = '';
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'category' => $category,
            'status' => $status,
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

        return $this->render('create', ['model' => $form]);
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
        ]);
    }

    public function actionPublish($id)
    {
        $model = $this->findModel($id);
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
        $model->status = PortalAssignment::STATUS_UNPUBLISHED;
        try {
            if ($model->save()) {
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
        $model = PortalAssignment::findOne($id);
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('未找到指定的课程资料。');
    }

    private function loadAndSave(PortalAssignmentForm $form, PortalAssignment $assignment)
    {
        if (!$form->load(Yii::$app->request->post())) {
            return false;
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

        $assignment->title = $form->title;
        $assignment->category = $form->category;
        $assignment->owner_label = $form->owner_label;
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
        if (!$form->uploadFile->saveAs($absolutePath, false)) {
            $form->addError('uploadFile', '文件保存失败，请稍后重试。');
            Yii::$app->session->setFlash('error', '文件上传失败。');
            return null;
        }

        return 'data/' . $categoryDirectory . '/' . $fileName;
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
            @unlink($absolutePath);
        }
    }
}

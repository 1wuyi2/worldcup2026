<?php

/**
 * 模块 A 前台留言板页面。
 *
 * @author wuyi
 * @date 2026-11-20
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $model common\models\PortalMessage */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '留言板';
$this->params['breadcrumbs'][] = $this->title;
$messages = $dataProvider->getModels();
?>
<div class="guestbook-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading"><strong>发表留言</strong></div>
        <div class="panel-body">
            <p class="text-muted">留言提交后需要管理员审核，审核通过后才会公开显示。</p>
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'display_name')->textInput([
                'maxlength' => 60,
                'autocomplete' => 'name',
            ]) ?>
            <?= $form->field($model, 'content')->textarea([
                'rows' => 5,
                'maxlength' => 2000,
            ]) ?>
            <div class="form-group">
                <?= Html::submitButton('提交留言', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <h2>公开留言</h2>
    <?php if (empty($messages)): ?>
        <div class="alert alert-info">目前还没有已发布的留言，欢迎留下第一条留言。</div>
    <?php else: ?>
        <?php foreach ($messages as $message): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong><?= Html::encode($message->display_name) ?></strong>
                    <span class="text-muted pull-right">
                        <?= Html::encode(Yii::$app->formatter->asDatetime($message->created_at)) ?>
                    </span>
                </div>
                <div class="panel-body">
                    <p><?= nl2br(Html::encode($message->content)) ?></p>

                    <?php if ($message->reply !== null): ?>
                        <div class="well well-sm">
                            <strong>管理员回复：</strong>
                            <p><?= nl2br(Html::encode($message->reply->content)) ?></p>
                            <small class="text-muted">
                                <?= Html::encode(Yii::$app->formatter->asDatetime($message->reply->updated_at)) ?>
                            </small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?= LinkPager::widget([
            'pagination' => $dataProvider->pagination,
        ]) ?>
    <?php endif; ?>
</div>

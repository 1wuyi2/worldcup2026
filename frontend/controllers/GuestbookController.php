<?php

/**
 * 模块 A 前台留言板控制器。
 *
 * @author wuyi
 * @date 2026-11-20
 */

namespace frontend\controllers;

use common\models\PortalMessage;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class GuestbookController extends Controller
{
    const RATE_LIMIT_WINDOW = 600;
    const RATE_LIMIT_MAX_SUBMISSIONS = 5;
    const RATE_LIMIT_COOLDOWN = 20;
    const DUPLICATE_TTL = 600;

    public function actionIndex()
    {
        $model = new PortalMessage();
        $model->setScenario(PortalMessage::SCENARIO_PUBLIC_CREATE);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->status = PortalMessage::STATUS_PENDING;

            if ($model->validate() && $this->validatePublicLength($model)) {
                $rateLimitError = $this->getRateLimitError($model);
                if ($rateLimitError === null) {
                    try {
                        if ($model->save()) {
                            $this->recordSubmission($model);
                            Yii::$app->session->setFlash('success', '留言提交成功，审核通过后将在前台显示。');
                            return $this->redirect(['index']);
                        }
                    } catch (\Throwable $exception) {
                        Yii::error('Guestbook message save failed: ' . get_class($exception), __METHOD__);
                    }

                    Yii::$app->session->setFlash('error', '留言提交失败，请检查内容后重试。');
                } else {
                    $model->addError('content', $rateLimitError);
                }
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => PortalMessage::find()
                ->with('reply')
                ->where(['status' => PortalMessage::STATUS_PUBLISHED])
                ->orderBy(['created_at' => SORT_DESC, 'id' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    private function getRateLimitError(PortalMessage $model)
    {
        $now = time();
        $keys = $this->getRateLimitKeys($model);
        $state = Yii::$app->cache->get($keys['window']);

        if (!is_array($state) || !isset($state['started_at'], $state['last_at'], $state['count'])) {
            $state = [
                'started_at' => $now,
                'last_at' => 0,
                'count' => 0,
            ];
        }

        if ($state['started_at'] <= $now - self::RATE_LIMIT_WINDOW) {
            $state = [
                'started_at' => $now,
                'last_at' => 0,
                'count' => 0,
            ];
        }

        if ($state['last_at'] > $now - self::RATE_LIMIT_COOLDOWN) {
            return '提交过于频繁，请稍后再试。';
        }
        if ($state['count'] >= self::RATE_LIMIT_MAX_SUBMISSIONS) {
            return '短时间内提交次数过多，请稍后再试。';
        }
        if (Yii::$app->cache->get($keys['duplicate']) !== false) {
            return '相同留言已经提交，请勿重复发送。';
        }

        return null;
    }

    private function validatePublicLength(PortalMessage $model)
    {
        if (mb_strlen($model->content, 'UTF-8') > 2000) {
            $model->addError('content', '留言内容不能超过 2000 个字符。');
            return false;
        }

        return true;
    }

    private function recordSubmission(PortalMessage $model)
    {
        $now = time();
        $keys = $this->getRateLimitKeys($model);
        $state = Yii::$app->cache->get($keys['window']);

        if (!is_array($state) || !isset($state['started_at'], $state['count'])
            || $state['started_at'] <= $now - self::RATE_LIMIT_WINDOW
        ) {
            $state = [
                'started_at' => $now,
                'count' => 0,
            ];
        }

        $state['last_at'] = $now;
        ++$state['count'];

        $remainingWindow = max(1, self::RATE_LIMIT_WINDOW - ($now - $state['started_at']));
        Yii::$app->cache->set($keys['window'], $state, $remainingWindow);
        Yii::$app->cache->set($keys['duplicate'], true, self::DUPLICATE_TTL);
    }

    private function getRateLimitKeys(PortalMessage $model)
    {
        $clientAddress = Yii::$app->request->userIP;
        if (!is_string($clientAddress) || $clientAddress === '') {
            $clientAddress = 'unknown';
        }

        $clientHash = hash('sha256', $clientAddress);
        $contentHash = hash('sha256', $clientAddress . "\n" . $model->display_name . "\n" . $model->content);

        return [
            'window' => 'portal-guestbook-window-' . $clientHash,
            'duplicate' => 'portal-guestbook-duplicate-' . $contentHash,
        ];
    }
}

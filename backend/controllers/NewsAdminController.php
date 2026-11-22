<?php

/**
 * 模块 B 后台公共权限和记录查找规则。
 * @author XPC
 * @date 2026-11-22
 */

namespace backend\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

abstract class NewsAdminController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [['allow' => true, 'roles' => ['manageNews']]],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => ['delete' => ['POST'], 'publish' => ['POST'], 'withdraw' => ['POST']],
            ],
        ];
    }

    protected function findRecord($class, $id)
    {
        if (is_scalar($id) && preg_match('/^[1-9][0-9]{0,19}$/D', (string) $id)) {
            $model = $class::findOne(['id' => $id]);
            if ($model !== null) {
                return $model;
            }
        }
        throw new NotFoundHttpException('未找到该新闻记录。');
    }
}

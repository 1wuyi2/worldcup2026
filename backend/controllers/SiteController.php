<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\BackendLoginForm;
use common\components\BackendAccess;

/**
 * 后台系统首页及认证控制器。
 *
 * @author wuyi
 * @date 2026-11-27
 */
class SiteController extends Controller
{
    /**
     * 后台首页访问控制及请求方式限制。
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],

                    // 已登录用户始终可以主动退出。
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                    // 后台首页必须具有实际管理权限。
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return BackendAccess::canAccess(
                                Yii::$app->user->id
                            );
                        },
                    ],
                ],
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'login' => ['get', 'post'],
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * 错误页面。
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * 后台首页。
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 后台管理员登录。
     */
    public function actionLogin()
{
    if (!Yii::$app->user->isGuest) {
        return $this->goHome();
    }

    // 后台登录使用独立认证布局。
    $this->layout = 'auth';

    $model = new BackendLoginForm();

        if (
            $model->load(Yii::$app->request->post())
            && $model->login()
        ) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * 退出后台管理系统。
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
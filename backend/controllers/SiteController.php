<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\BackendLoginForm;
use common\components\BackendAccess;
use common\models\PortalTeam;
use common\models\PortalMember;
use common\models\PortalAssignment;
use common\models\PortalMessage;

use common\models\NewsArticle;
use common\models\NewsCategory;
use common\models\NewsTag;

use common\models\FootballMatch;
use common\models\FootballTeam;
use common\models\FootballPlayer;
use common\models\FootballStadium;
use common\models\FootballMatchGoal;

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
    $user = Yii::$app->user;

    $modules = [];
    $overview = [];

    // =====================================
    // 模块 A：团队门户
    // =====================================

    if ($user->can('managePortal')) {

        $teamCount = (int) PortalTeam::find()->count();

        $memberCount = (int) PortalMember::find()->count();

        $assignmentCount = (int) PortalAssignment::find()->count();

        $messageCount = (int) PortalMessage::find()->count();

        $overview[] = [
            'label' => '团队成员',
            'value' => $memberCount,
            'icon' => 'users',
            'module' => '团队门户',
            'url' => ['/portal-member/index'],
        ];

        $overview[] = [
            'label' => '课程作业',
            'value' => $assignmentCount,
            'icon' => 'folder',
            'module' => '团队门户',
            'url' => ['/portal-assignment/index'],
        ];

        $modules[] = [
            'code' => 'A',
            'title' => '团队门户',
            'description' => '管理团队资料、成员、课程成果与留言。',
            'url' => ['/portal-team/index'],
            'items' => [
                [
                    'label' => '团队资料',
                    'count' => $teamCount,
                    'url' => ['/portal-team/index'],
                ],
                [
                    'label' => '成员管理',
                    'count' => $memberCount,
                    'url' => ['/portal-member/index'],
                ],
                [
                    'label' => '课程作业',
                    'count' => $assignmentCount,
                    'url' => ['/portal-assignment/index'],
                ],
                [
                    'label' => '留言管理',
                    'count' => $messageCount,
                    'url' => ['/portal-guestbook/index'],
                ],
            ],
        ];
    }


    // =====================================
    // 模块 B：新闻资讯
    // =====================================

    if ($user->can('manageNews')) {

        $articleCount = (int) NewsArticle::find()->count();

        $categoryCount = (int) NewsCategory::find()->count();

        $tagCount = (int) NewsTag::find()->count();

        $overview[] = [
            'label' => '新闻文章',
            'value' => $articleCount,
            'icon' => 'news',
            'module' => '新闻资讯',
            'url' => ['/news/index'],
        ];

        $modules[] = [
            'code' => 'B',
            'title' => '新闻资讯',
            'description' => '管理世界杯新闻文章、分类及标签。',
            'url' => ['/news/index'],
            'items' => [
                [
                    'label' => '新闻文章',
                    'count' => $articleCount,
                    'url' => ['/news/index'],
                ],
                [
                    'label' => '新闻分类',
                    'count' => $categoryCount,
                    'url' => ['/news-category/index'],
                ],
                [
                    'label' => '新闻标签',
                    'count' => $tagCount,
                    'url' => ['/news-tag/index'],
                ],
            ],
        ];
    }


    // =====================================
    // 模块 C：足球数据
    // =====================================

    if ($user->can('manageFootball')) {

        $matchCount = (int) FootballMatch::find()->count();

        $teamCount = (int) FootballTeam::find()->count();

        $playerCount = (int) FootballPlayer::find()->count();

        $stadiumCount = (int) FootballStadium::find()->count();

        $goalCount = (int) FootballMatchGoal::find()->count();

        $overview[] = [
            'label' => '世界杯比赛',
            'value' => $matchCount,
            'icon' => 'trophy',
            'module' => '足球数据',
            'url' => ['/match/index'],
        ];

        $modules[] = [
            'code' => 'C',
            'title' => '足球数据',
            'description' => '维护世界杯比赛、球队、球员及场馆数据。',
            'url' => ['/match/index'],
            'items' => [
                [
                    'label' => '比赛管理',
                    'count' => $matchCount,
                    'url' => ['/match/index'],
                ],
                [
                    'label' => '球队管理',
                    'count' => $teamCount,
                    'url' => ['/football-team/index'],
                ],
                [
                    'label' => '球员管理',
                    'count' => $playerCount,
                    'url' => ['/football-player/index'],
                ],
                [
                    'label' => '场馆管理',
                    'count' => $stadiumCount,
                    'url' => ['/stadium/index'],
                ],
                [
                    'label' => '进球记录',
                    'count' => $goalCount,
                    'url' => ['/match-goal/index'],
                ],
            ],
        ];
    }

    return $this->render('index', [
        'overview' => $overview,
        'modules' => $modules,
    ]);
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
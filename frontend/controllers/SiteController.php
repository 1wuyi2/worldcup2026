<?php
namespace frontend\controllers;

use Yii;

use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\FootballMatch;
use common\models\FootballTeam;
use common\models\FootballPlayer;
use common\models\FootballStadium;
use common\models\NewsArticle;
use yii\web\NotFoundHttpException;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
{
    // 1. 首页赛事精选
    $featuredMatches = FootballMatch::find()
        ->with(['homeTeam', 'awayTeam', 'stadium'])
        ->where(['status' => 2])
        ->orderBy([
            'match_time' => SORT_DESC,
            'id' => SORT_DESC,
        ])
        ->limit(4)
        ->all();

    // 2. 世界杯数据统计
    $worldCupStats = [
        'teams' => FootballTeam::find()->count(),
        'players' => FootballPlayer::find()->count(),
        'stadiums' => FootballStadium::find()->count(),
    ];

    // 3. 首页精选球队
    // 按小组和名称排序，每个小组选取一支球队。
    $allTeams = FootballTeam::find()
        ->orderBy([
            'group_name' => SORT_ASC,
            'name' => SORT_ASC,
            'id' => SORT_ASC,
        ])
        ->all();

    $featuredTeams = [];
    $selectedGroups = [];

    foreach ($allTeams as $team) {
        $group = $team->group_name ?: '未分组';

        if (isset($selectedGroups[$group])) {
            continue;
        }

        $featuredTeams[] = $team;
        $selectedGroups[$group] = true;

        if (count($featuredTeams) >= 8) {
            break;
        }
    }

    // 4. 首页最新新闻
    // 复用新闻模块的公开查询规则。
    $latestNews = NewsArticle::publishedQuery()
        ->with('category')
        ->orderBy([
            'article.published_at' => SORT_DESC,
            'article.id' => SORT_DESC,
        ])
        ->limit(3)
        ->all();

    return $this->render('index', [
        'featuredMatches' => $featuredMatches,
        'featuredTeams' => $featuredTeams,
        'worldCupStats' => $worldCupStats,
        'latestNews' => $latestNews,
    ]);
}

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
 * 前台普通用户注册。
 *
 * 注册成功后账号直接激活，
 * 跳转至登录页面，由用户主动登录。
 */
public function actionSignup()
{
    $model = new SignupForm();

    if (
        $model->load(Yii::$app->request->post())
        && $model->signup()
    ) {
        Yii::$app->session->setFlash(
            'success',
            '注册成功，请使用新账号登录。'
        );

        return $this->redirect(['/site/login']);
    }

    return $this->render('signup', [
        'model' => $model,
    ]);
}

        /**
     * 当前部署不提供邮件找回密码功能。
     */
    public function actionRequestPasswordReset()
    {
        throw new NotFoundHttpException(
            '当前站点暂不提供邮件找回密码功能。'
        );
    }

    /**
     * 停用原有密码重置链接。
     */
    public function actionResetPassword($token = null)
    {
        throw new NotFoundHttpException(
            '当前站点暂不提供密码重置功能。'
        );
    }

    /**
     * 新注册用户直接激活，
     * 不再通过邮件验证链接激活账号。
     */
    public function actionVerifyEmail($token = null)
    {
        throw new NotFoundHttpException(
            '当前站点已停用邮件验证功能。'
        );
    }

    /**
     * 停用重新发送验证邮件功能。
     */
    public function actionResendVerificationEmail()
    {
        throw new NotFoundHttpException(
            '当前站点已停用验证邮件发送功能。'
        );
    }
}
<?php

namespace backend\components;

use common\components\BackendAccess;
use yii\base\BootstrapInterface;
use yii\web\Application;
use yii\web\ForbiddenHttpException;

/**
 * 后台全局访问守卫。
 *
 * 每次执行后台 Action 前检查登录状态及后台访问资格。
 * 不替代各业务模块已有的细粒度 RBAC 检查。
 *
 * @author wuyi
 * @date 2026-11-27
 */
class BackendAccessGuard implements BootstrapInterface
{
    /**
     * 注册后台应用级 Action 访问检查。
     *
     * @param Application $app
     */
    public function bootstrap($app)
    {
        $app->on(
            Application::EVENT_BEFORE_ACTION,
            function ($event) use ($app) {
                $route = $event->action->uniqueId;

                /*
                 * 错误页面必须允许访问，
                 * 避免权限异常触发递归检查。
                 */
                if ($route === 'site/error') {
                    return;
                }

                $user = $app->getUser();

                /*
                 * 允许执行登录及退出入口。
                 *
                 * 登录资格由 BackendLoginForm 检查；
                 * 退出仍由 SiteController 的 AccessControl、
                 * VerbFilter 和 CSRF 防护负责。
                 */
                if ($route === 'site/logout') {
                    return;
                }

                if ($user->getIsGuest()) {
                    if ($route === 'site/login') {
                        return;
                    }

                    // 未登录用户进入后台登录页面。
                    $event->isValid = false;

                    $user->loginRequired();

                    return;
                }

                /*
                 * 已登录用户必须仍具有实际后台管理权限。
                 * 防止管理权限撤销后继续使用原 Session。
                 */
                if (!BackendAccess::canAccess($user->getId())) {
                    // 只注销当前后台应用的登录状态。
                    $user->logout();

                    $event->isValid = false;

                    throw new ForbiddenHttpException(
                        '该账号已不具有后台管理权限，请重新登录。'
                    );
                }
            }
        );
    }
}
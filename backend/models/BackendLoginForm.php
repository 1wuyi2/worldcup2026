<?php

namespace backend\models;

use Yii;
use common\models\LoginForm;
use common\components\BackendAccess;

/**
 * 后台专用登录表单。
 *
 * 继承公共 LoginForm 的账号密码验证规则，
 * 在建立后台登录状态之前额外验证管理权限。
 *
 * @author wuyi
 * @date 2026-11-27
 */
class BackendLoginForm extends LoginForm
{
    /**
     * 登录后台管理系统。
     *
     * @return bool
     */
    public function login()
    {
        // 使用原有用户名、密码及 rememberMe 验证。
        if (!$this->validate()) {
            return false;
        }

        // getUser() 继承自公共 LoginForm。
        $user = $this->getUser();

        if ($user === null) {
            return false;
        }

        // 在建立 Session 之前检查后台访问资格。
        if (!BackendAccess::canAccess($user->id)) {
            $this->addError(
                'username',
                '该账号没有后台管理权限。'
            );

            return false;
        }

        // 保留原有记住登录状态机制。
        return Yii::$app->user->login(
            $user,
            $this->rememberMe
                ? 3600 * 24 * 30
                : 0
        );
    }
}
<?php

/**
 * 初始化赛事管理权限并授权本机测试用户。
 * @author lzs
 * @date 2026-11-20
 */

namespace console\controllers;

use common\models\User;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\rbac\DbManager;

class FootballAccessController extends Controller
{
    public const PERMISSION = 'manageFootball';

    public function actionInit()
    {
        $auth = $this->getManager();
        if ($auth === null) {
            return ExitCode::CONFIG;
        }

        $permission = $auth->getPermission(self::PERMISSION);
        if ($permission === null) {
            $permission = $auth->createPermission(self::PERMISSION);
            $permission->description = '管理世界杯球队与赛事';
            $auth->add($permission);
        }

        $admin = $auth->getRole('admin');
        if ($admin === null) {
            $admin = $auth->createRole('admin');
            $admin->description = '系统管理员';
            $auth->add($admin);
        }
        if (!$auth->hasChild($admin, $permission)) {
            $auth->addChild($admin, $permission);
        }

        $this->stdout("赛事权限已初始化。\n");
        return ExitCode::OK;
    }

    public function actionAssign($username)
    {
        $auth = $this->getManager();
        if ($auth === null) {
            return ExitCode::CONFIG;
        }

        $user = User::findByUsername($username);
        if ($user === null) {
            $this->stderr("指定账号不存在或尚未激活。\n");
            return ExitCode::NOUSER;
        }

        $permission = $auth->getPermission(self::PERMISSION);
        if ($permission === null) {
            $this->stderr("请先运行 yii football-access/init。\n");
            return ExitCode::CONFIG;
        }

        if (!$auth->checkAccess((string) $user->id, self::PERMISSION)) {
            $auth->assign($permission, (string) $user->id);
        }

        $this->stdout("该账号拥有赛事管理权限。\n");
        return ExitCode::OK;
    }

    private function getManager(): ?DbManager
    {
        $auth = Yii::$app->authManager;
        if (!$auth instanceof DbManager || $auth->db->schema->getTableSchema($auth->assignmentTable) === null) {
            $this->stderr("请先安装 Yii2 官方 RBAC Migration。\n");
            return null;
        }

        return $auth;
    }
}

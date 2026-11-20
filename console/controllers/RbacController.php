<?php

/**
 * 项目 RBAC 角色、权限及本地管理员分配控制台命令。
 *
 * @author wuyi
 * @date 2026-11-19
 */

namespace console\controllers;

use common\models\User;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\rbac\DbManager;

class RbacController extends Controller
{
    const ROLE_ADMIN = 'admin';
    const PERMISSION_MANAGE_PORTAL = 'managePortal';

    /**
     * 创建或复用 admin 角色、managePortal 权限及其继承关系。
     */
    public function actionInit()
    {
        $authManager = $this->getDbManager();
        if ($authManager === null || !$this->ensureRbacTables($authManager)) {
            return ExitCode::CONFIG;
        }

        $permission = $authManager->getPermission(self::PERMISSION_MANAGE_PORTAL);
        $role = $authManager->getRole(self::ROLE_ADMIN);

        if ($permission === null && $authManager->getRole(self::PERMISSION_MANAGE_PORTAL) !== null) {
            $this->stderr('名称 managePortal 已被角色占用，无法创建同名权限。' . PHP_EOL);
            return ExitCode::DATAERR;
        }
        if ($role === null && $authManager->getPermission(self::ROLE_ADMIN) !== null) {
            $this->stderr('名称 admin 已被权限占用，无法创建同名角色。' . PHP_EOL);
            return ExitCode::DATAERR;
        }

        if ($permission === null) {
            $permission = $authManager->createPermission(self::PERMISSION_MANAGE_PORTAL);
            $permission->description = '管理团队门户与团队成员';
            if (!$authManager->add($permission)) {
                $this->stderr('managePortal 权限创建失败。' . PHP_EOL);
                return ExitCode::UNSPECIFIED_ERROR;
            }
            $this->stdout('已创建 managePortal 权限。' . PHP_EOL);
        } else {
            $this->stdout('managePortal 权限已存在，跳过创建。' . PHP_EOL);
        }

        if ($role === null) {
            $role = $authManager->createRole(self::ROLE_ADMIN);
            $role->description = '系统管理员';
            if (!$authManager->add($role)) {
                $this->stderr('admin 角色创建失败。' . PHP_EOL);
                return ExitCode::UNSPECIFIED_ERROR;
            }
            $this->stdout('已创建 admin 角色。' . PHP_EOL);
        } else {
            $this->stdout('admin 角色已存在，跳过创建。' . PHP_EOL);
        }

        if (!$authManager->hasChild($role, $permission)) {
            if (!$authManager->addChild($role, $permission)) {
                $this->stderr('admin → managePortal 继承关系创建失败。' . PHP_EOL);
                return ExitCode::UNSPECIFIED_ERROR;
            }
            $this->stdout('已将 managePortal 权限授予 admin 角色。' . PHP_EOL);
        } else {
            $this->stdout('admin 已拥有 managePortal 权限，跳过创建关系。' . PHP_EOL);
        }

        return ExitCode::OK;
    }

    /**
     * 将 admin 角色显式分配给指定的已激活用户。
     *
     * @param string $userId 用户 ID
     * @param string $username 需要与数据库完全一致的用户名
     */
    public function actionAssignAdmin($userId, $username)
    {
        $authManager = $this->getDbManager();
        if ($authManager === null || !$this->ensureRbacTables($authManager)) {
            return ExitCode::CONFIG;
        }

        $normalizedUserId = $this->normalizeUserId($userId);
        if ($normalizedUserId === null) {
            return ExitCode::USAGE;
        }
        if (!is_string($username) || $username === '') {
            $this->stderr('username 不能为空。' . PHP_EOL);
            return ExitCode::USAGE;
        }

        $user = User::findOne($normalizedUserId);
        if ($user === null) {
            $this->stderr('指定用户不存在。' . PHP_EOL);
            return ExitCode::NOUSER;
        }
        if ($user->username !== $username) {
            $this->stderr('用户名与数据库记录不一致，拒绝分配管理员角色。' . PHP_EOL);
            return ExitCode::DATAERR;
        }
        if ((int) $user->status !== User::STATUS_ACTIVE) {
            $this->stderr('指定用户不是已激活状态，拒绝分配管理员角色。' . PHP_EOL);
            return ExitCode::NOPERM;
        }

        $role = $authManager->getRole(self::ROLE_ADMIN);
        if ($role === null) {
            $this->stderr('admin 角色不存在，请先执行 yii rbac/init。' . PHP_EOL);
            return ExitCode::CONFIG;
        }

        $assignment = $authManager->getAssignment(self::ROLE_ADMIN, (string) $user->id);
        if ($assignment !== null) {
            $this->stdout('该用户已经拥有 admin 角色，无需重复分配。' . PHP_EOL);
            return ExitCode::OK;
        }

        $authManager->assign($role, (string) $user->id);
        $this->stdout('admin 角色分配成功。' . PHP_EOL);

        return ExitCode::OK;
    }

    /**
     * 只读检查指定用户是否拥有某项权限。
     *
     * @param string $userId 用户 ID
     * @param string $permissionName 权限名称
     */
    public function actionCheck($userId, $permissionName)
    {
        $authManager = $this->getDbManager();
        if ($authManager === null || !$this->ensureRbacTables($authManager)) {
            return ExitCode::CONFIG;
        }

        $normalizedUserId = $this->normalizeUserId($userId);
        if ($normalizedUserId === null) {
            return ExitCode::USAGE;
        }
        if (!is_string($permissionName) || $permissionName === '') {
            $this->stderr('permissionName 不能为空。' . PHP_EOL);
            return ExitCode::USAGE;
        }

        $user = User::findOne($normalizedUserId);
        if ($user === null) {
            $this->stderr('指定用户不存在。' . PHP_EOL);
            return ExitCode::NOUSER;
        }
        if ($authManager->getPermission($permissionName) === null) {
            $this->stderr('指定权限不存在。' . PHP_EOL);
            return ExitCode::DATAERR;
        }

        $hasPermission = $authManager->checkAccess((string) $user->id, $permissionName);
        $this->stdout($hasPermission ? 'ALLOW' . PHP_EOL : 'DENY' . PHP_EOL);

        return $hasPermission ? ExitCode::OK : ExitCode::NOPERM;
    }

    private function getDbManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            $this->stderr('authManager 未配置为 yii\\rbac\\DbManager。' . PHP_EOL);
            return null;
        }

        return $authManager;
    }

    private function ensureRbacTables(DbManager $authManager)
    {
        $tables = [
            $authManager->ruleTable,
            $authManager->itemTable,
            $authManager->itemChildTable,
            $authManager->assignmentTable,
        ];

        foreach ($tables as $table) {
            if ($authManager->db->schema->getTableSchema($table, true) === null) {
                $this->stderr('RBAC 数据表尚未安装，请先执行官方 RBAC Migration。' . PHP_EOL);
                return false;
            }
        }

        return true;
    }

    private function normalizeUserId($userId)
    {
        $userId = (string) $userId;
        if ($userId === '' || !ctype_digit($userId) || (int) $userId < 1) {
            $this->stderr('userId 必须是大于 0 的整数。' . PHP_EOL);
            return null;
        }

        return (int) $userId;
    }
}

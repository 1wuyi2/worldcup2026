<?php

namespace common\components;

use Yii;

/**
 * WorldCup2026 后台访问资格判断。
 *
 * 前后台共享同一套判断规则，
 * 但分别维护各自的认证状态。
 *
 * @author wuyi
 * @date 2026-11-27
 */
final class BackendAccess
{
    /**
     * 允许进入后台的业务管理权限。
     */
    const PERMISSIONS = [
        'managePortal',
        'manageNews',
        'manageFootball',
    ];

    /**
     * 判断指定用户是否具有后台访问资格。
     *
     * 通过 RBAC 实际权限计算，
     * 而不是判断用户名或直接分配的角色名称。
     *
     * @param int|string|null $userId
     * @return bool
     */
    public static function canAccess($userId)
    {
        if ($userId === null || $userId === '') {
            return false;
        }

        $authManager = Yii::$app->getAuthManager();

        if ($authManager === null) {
            return false;
        }

        foreach (self::PERMISSIONS as $permission) {
            if (
                $authManager->checkAccess(
                    (string) $userId,
                    $permission
                )
            ) {
                return true;
            }
        }

        return false;
    }
}
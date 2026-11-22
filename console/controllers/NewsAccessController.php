<?php

/**
 * 模块 B 幂等初始化新闻权限，复用全站 admin 角色。
 * @author XPC
 * @date 2026-11-22
 */

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\rbac\DbManager;

class NewsAccessController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        if (!$auth instanceof DbManager) {
            $this->stderr('请将 authManager 配置为 DbManager。' . PHP_EOL);
            return ExitCode::CONFIG;
        }
        foreach ([$auth->itemTable, $auth->itemChildTable, $auth->assignmentTable, $auth->ruleTable] as $table) {
            if ($auth->db->schema->getTableSchema($table) === null) {
                $this->stderr('请先安装 Yii2 官方 RBAC Migration。' . PHP_EOL);
                return ExitCode::CONFIG;
            }
        }
        if ($auth->getRole('manageNews') !== null || $auth->getPermission('admin') !== null) {
            $this->stderr('admin 或 manageNews 名称被其他类型占用，请先核对现有权限。' . PHP_EOL);
            return ExitCode::CONFIG;
        }
        $auth->db->transaction(function () use ($auth) {
            $permission = $auth->getPermission('manageNews');
            if ($permission === null) {
                $permission = $auth->createPermission('manageNews');
                $permission->description = '管理世界杯新闻分类、标签和文章';
                $auth->add($permission);
            }
            $role = $auth->getRole('admin');
            if ($role === null) {
                $role = $auth->createRole('admin');
                $role->description = '系统管理员';
                $auth->add($role);
            }
            if (!$auth->hasChild($role, $permission)) {
                $auth->addChild($role, $permission);
            }
        });
        $this->stdout('新闻权限已初始化；现有 admin 用户继承 manageNews。' . PHP_EOL);
        return ExitCode::OK;
    }
}

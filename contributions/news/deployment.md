# 模块 B：新闻资讯部署与交接

作者：XPC

日期：2026-11-22

## 环境

复用项目现有 PHP、Composer、Yii2 和 MariaDB/MySQL 环境，不需要额外安装新闻插件、Node.js、富文本编辑器或下载依赖。本模块已在 PHP 8.0.30、Yii2 2.0.55、MariaDB 10.4.32 下验证。

数据库连接沿用本地不入库的 `common/config/main-local.php`。当前开发数据库为 `worldcup2026_db`，位于本机 `127.0.0.1:3307`。部署前核对目标连接，迁移作用于配置指定的数据库。

## 当前机器

本次开发已在当前本地库执行新闻迁移和新闻权限初始化，XPC 已通过现有 `admin` 角色获得权限，无需再次安装依赖或重新建库。直接使用手册中的前后台地址即可开始录入内容。

数据库和用户的前台 18080、后台 18081 服务保持运行。若服务已关闭，在两个 PowerShell 窗口分别运行：

```powershell
cd 'D:\Files\作业\互联网数据库开发\worldcup2026'
& 'D:\Softwares\xampp\php\php.exe' -S 127.0.0.1:18080 -t frontend/web
```

```powershell
cd 'D:\Files\作业\互联网数据库开发\worldcup2026'
& 'D:\Softwares\xampp\php\php.exe' -S 127.0.0.1:18081 -t backend/web
```

## 团队成员合并后

下列命令在项目根目录执行，以 PHP 已加入 PATH 为例；当前机器可替换为上面的 PHP 绝对路径。

```powershell
php yii migrate --interactive=0
php yii migrate --migrationPath=@yii/rbac/migrations --interactive=0
php yii news-access/init
```

第一条执行所有尚未应用的业务迁移，新增新闻四表；第二条为已有团队权限体系所需的 Yii 官方 RBAC 迁移，执行过的迁移会跳过。`news-access/init` 可重复执行，会补建 `manageNews` 并添加到共享 `admin` 角色，不清空 A/C 权限，也不直接修改已有用户分配。

已有 `admin` 账号自动获得新闻权限。如果是新环境，先通过前台注册并完成账号激活，再使用团队已有的管理员命令：

```powershell
php yii rbac/assign-admin <用户ID> <用户名>
php yii rbac/check <用户ID> manageNews
```

ID 和用户名应对应同一个已激活的网站账号，不是数据库 root 用户。保留 A/C 原有权限初始化流程。

## 路由交接

| 应用 | 路由 | 用途 |
| --- | --- | --- |
| frontend | `news/index` | 公开新闻与筛选 |
| frontend | `news/view&id=<ID>` | 公开详情 |
| backend | `news/index` | 文章管理 |
| backend | `news-category/index` | 分类管理 |
| backend | `news-tag/index` | 标签管理 |

创建、编辑、详情路由分别使用 `create`、`update&id=<ID>`、`view&id=<ID>`；详情页提供发布与撤稿按钮。前后台的 `news` 控制器位于不同应用，路由不会冲突。公共导航调整时，后台入口可使用 `Yii::$app->user->can('manageNews')` 控制显示；服务端仍独立检查权限。

## 自动化测试

测试使用独立数据库。本机已经配置本地 `common/config/test-local.php`，指定 `worldcup2026_news_test`。团队成员需要创建自己的测试库并在该文件覆盖 DSN；凭据沿用对应本地配置。不要将测试库 DSN 指向正式或共享业务库。

```powershell
php yii_test migrate --interactive=0
php vendor/bin/codecept run -c common unit models/NewsArticleTest.php --no-colors
```

本次结果：6 项测试、56 次断言通过。每项测试用事务隔离并回滚创建的数据。另在独立测试服务完成了 45 项实际 HTTP 检查，范围见实现说明。

## 交付状态

功能、迁移、权限命令、模型测试和模块说明已完成。正式新闻素材尚需整理录入；完整课程交付还包括公共导航与视觉统一、最终 SQL 导出、报告和演示，按团队集成安排继续进行。

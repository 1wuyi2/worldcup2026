# WorldCup2026：模块 A + C 集成说明

## 1. 版本来源与合并策略

- 模块 A：用户提供的 `module-A-portal.zip`，包含团队门户、留言、作业管理、RBAC、安全目录配置和阶段 6 文档。
- 模块 C：用户提供的 `module-C.zip`，包含世界杯国家队、球员、场馆、比赛与进球管理。
- 以 A 的项目快照为基线，补入 C 独有的 29 个文件；两份快照中的共有文件逐字节一致，不存在同路径内容冲突。
- 仅额外修改 `frontend/views/layouts/main.php` 与 `backend/views/layouts/main.php`，增加 A/C 路由导航；原业务模型、控制器、Migration 不改写。
- 此整合包仅用于 A+C 功能验收。模块 B、最终全站布局与正式 `data/install.sql` 后续由小组合并。

## 2. 运行前须知

该 ZIP 是 **源码快照，不是开箱即用的 XAMPP 安装包**：Git 导出的源码不包含 `vendor/`、本地 `*-local.php`、运行时生成的 `frontend/web/index.php` 与 `backend/web/index.php`。必须按照项目现有 Yii2 Advanced 部署说明进行 Composer 安装、`init`、数据库与本地配置初始化。**不要解压覆盖现有正在运行的项目目录**。优先用新的项目副本和独立测试数据库检验，或在已有项目及数据库完成备份后按 Git 集成流程操作。

注意：此源码包并未包含你本地 MariaDB 中的业务数据、账号和授权。文件目录 `data/` 包含源码仓库里原有的课程 PDF 与安全访问配置，请对其内容做课程提交与隐私审查。

## 3. 数据库结构与 Migration

模块 A（五张表）：`wc_portal_team`、`wc_portal_member`、`wc_portal_message`、`wc_portal_reply`、`wc_portal_assignment`。

模块 C（五张表）：`wc_football_team`、`wc_football_player`、`wc_football_stadium`、`wc_football_match`、`wc_football_match_goal`。

代码对应：

- `console/migrations/m261119_190100_create_portal_tables.php`
- `console/migrations/m261120_233000_create_football_tables.php`

A、C 合计 10 张业务表；按设计文档最终加上 B 的 4 张，总计 14 张业务表，不计 Yii2 框架和 RBAC 表。

### 已有 A 数据库整合 C

1. **先备份业务数据库**。此项目曾遇到 MariaDB `mysql.db` 损坏与修复后授权丢失，切勿用未确认的备份覆盖正在运行的库。
2. 在数据库管理员帮助下核对 `worldcup2026_db` 的现有表、Migration 历史、RBAC 数据，以及网站账号权限。已授权的 `SELECT/INSERT/UPDATE/DELETE` **不足以运行建表 Migration**。
3. 使用具备该数据库所需 DDL 权限的临时部署连接运行（运行前请先核对迁移名单）：

   ```cmd
   D:\xampp\php\php.exe yii migrate/new --migrationPath=@console/migrations
   D:\xampp\php\php.exe yii migrate --migrationPath=@console/migrations
   ```

   不要在现有数据库中重复运行 A 已完成的迁移，不要重新执行已完成的官方 RBAC 迁移。
4. 执行完成后复验 10 张业务表；如果 Migration 报权限不足，停止并排查，不要重建表或清空库。
5. 使用原业务账号运行网站时，仅保留运行阶段所需的 CRUD 权限即可。

### 全新环境

依据 `contributions/portal/deployment.md` 配置 Yii2 Advanced、数据库和 RBAC。对空库执行普通 Migration、官方 RBAC Migration，随后初始化模块权限。注意 `data/install.sql` 仍未生成；未来最终 SQL 和全套建表 Migration 属于二选一的恢复路径，不得盲目叠加执行。

## 4. RBAC 集成

两模块共同使用 `common/config/main.php` 中 `yii\rbac\DbManager` 与名为 `admin` 的角色：

- A：`managePortal`，`yii rbac/init` 创建并链接 `admin`。
- C：`manageFootball`，`yii football-access/init` 创建并链接 `admin`。

在 RBAC 表已经安装、管理员已有 `admin` 角色的情况下，运行：

```cmd
D:\xampp\php\php.exe yii rbac/init
D:\xampp\php\php.exe yii football-access/init
D:\xampp\php\php.exe yii rbac/check 1 managePortal
D:\xampp\php\php.exe yii rbac/check 1 manageFootball
```

**注意：`1` 仅为本机示例 ID，实际应通过数据库中有效的已激活账号 ID 替换**。如果尚未分配管理员，使用 `yii rbac/assign-admin <实际ID> <用户名>`。C 提供的 `yii football-access/assign <用户名>` 会直接分配 C 权限；全站统一管理员建议用 `admin` 角色继承，不需对同一用户重复单独分配。

## 5. 前台验收路由

以本地 `http://127.0.0.1/worldcup2026/frontend/web/index.php?r=` 为前缀：

| 模块 | 路由 | 功能 |
| --- | --- | --- |
| A | `team/index` | 开发团队与成员 |
| A | `guestbook/index` | 访客留言 |
| A | `assignment/index` | 已发布课程资料 |
| C | `football/teams` | 国家队 |
| C | `football/players` | 球员（可按队筛选） |
| C | `football/stadiums` | 场馆 |
| C | `match/index` | 赛程与比分 |
| C | `match/view&id=<比赛ID>` | 比赛详情、进球记录 |

所有上方路由都应通过新导航进入。业务列表在无数据时可能显示空状态，需要合法的演示数据才能展示赛程。

## 6. 后台验收路由

以本地 `http://127.0.0.1/worldcup2026/backend/web/index.php?r=` 为前缀：

- A：`portal-team/index`、`portal-member/index`、`portal-guestbook/index`、`portal-assignment/index`。
- C：`football-team/index`、`football-player/index`、`stadium/index`、`match/index`、`match-goal/index`。

未登录用户应被要求登录，无 `managePortal` / `manageFootball` 权限者无法进入对应后台操作。C 的管理页面还包含模块内快捷导航。

## 7. 数据与静态文件安全

- `data/.htaccess` 必须保留：在支持 `AllowOverride` 的 Apache 环境下，静态访问 `data/` 下真实文件应被拒绝（403）。
- Yii2 的下载 Controller 仅允许已发布的文件记录；在新环境应复验直接 HTTP 访问 403、已发布下载成功、未发布下载失败。
- 推荐将正式站点的 Apache DocumentRoot 分别指向 `frontend/web`、`backend/web`，不能仅依赖 `.htaccess` 保护其他非 Web 目录。
- `common/config/main-local.php`、密钥、数据库密码、`vendor/` 和运行时文件不随源码包交付。
- 同学 C 分支的 Git 快照不包含其本地演示数据库记录，合并代码后不能据此宣称已有赛事演示数据。

## 8. 测试与集成边界

本整合包已做文件级冲突检查及 PHP 语法检查；**未在本环境运行真实 XAMPP / MariaDB / Yii2 HTTP 接口测试**。需要在本地逐项验收：

- A 的团队资料、留言审核/回复、上传、发布与下载；
- C 的球队、球员、场馆、赛程、比赛详情、进球后台 CRUD；
- 双模块管理员权限及无权用户的拒绝访问；
- C 数据校验、赛果与进球一致性；
- 数据库表数及外键、迁移历史；
- 前后台共用导航及未登录行为。

C 的 `common/tests/unit/models/FootballMatchTest.php` 会在数据库事务内生成测试数据。建议在单独的测试库执行，**不要直接在生产或已有业务库上运行**。

本集成包没有合并模块 B，也没有重写全站 README、最终课程交付 SQL、演示数据或视觉主题。

# 模块 A：团队门户与互动功能开发记录

## 一、模块概述

本模块是 WorldCup2026 课程项目的团队门户部分，主要负责团队介绍、成员信息管理、访客留言与管理员回复，以及团队和个人课程资料的管理与下载。

项目采用 Yii2 Advanced 2.0.55、PHP 8.0.30 和 MariaDB 10.4.32 开发，使用 GitHub 进行版本管理。本人在 `portal` 分支完成模块 A 的相关开发工作。

模块采用 Yii2 MVC 架构，数据库通过 Migration 管理表结构，数据访问使用 ActiveRecord，前后台分别实现公开展示和管理员维护功能。

## 二、阶段 0：项目环境检查

### 1. 环境与项目结构

首先检查已有 Yii2 Advanced 项目及本地开发环境，确认 PHP、Composer、Yii2 框架依赖及项目目录结构能够满足后续开发要求。

项目主要目录包括：

- `common/models/`：存放前后台共享的数据模型。
- `frontend/`：实现访客可访问的前台页面。
- `backend/`：实现管理员操作页面。
- `console/migrations/`：管理数据库表结构。
- `contributions/portal/`：记录模块开发、使用及部署相关内容。

### 2. 检查结果

确认 Yii2 Advanced 框架及 Composer 依赖完整，项目可以在本地 Windows + XAMPP 环境中运行。

本阶段主要完成项目结构和环境检查，没有修改业务代码。

## 三、阶段 0.5：数据库环境准备

### 1. 数据库配置

使用 XAMPP 启动 MariaDB 10.4.32，数据库服务端口为 `3307`。

为避免影响已有项目，单独创建数据库：

`worldcup2026_db`

数据库采用 `utf8mb4` 字符集。

随后修改本地 `common/config/main-local.php`，使 Yii2 连接项目专用数据库。

### 2. 连接问题处理

初次连接时出现 MariaDB 1044 权限错误，原因是原数据库账户没有访问新数据库的权限。

为此创建项目专用账户 `worldcup_user`，并授予其对 `worldcup2026_db` 的操作权限。

数据库密码保存在本地配置文件中，不提交至 Git 仓库。

### 3. 验证结果

使用临时 PHP 脚本加载 Yii2 控制台配置并执行只读 SQL 查询，成功获得：

```text
Database: worldcup2026_db
Version: 10.4.32-MariaDB
```

确认 Yii2 与 MariaDB 的连接正常后，删除临时检查脚本。

## 四、阶段 1：数据库 Migration 开发

### 1. 数据库表设计

根据模块 A 的数据库设计，使用 Yii2 Migration 创建五张业务表。

新增文件：

`console/migrations/m261119_190100_create_portal_tables.php`

对应的数据表为：

| 数据表 | 用途 |
|---|---|
| `wc_portal_team` | 团队基本资料 |
| `wc_portal_member` | 团队成员资料 |
| `wc_portal_message` | 访客留言 |
| `wc_portal_reply` | 管理员回复 |
| `wc_portal_assignment` | 团队及个人课程资料 |

### 2. 表关系与约束

五张表使用 InnoDB 存储引擎和 `utf8mb4` 字符集，主键采用无符号 BIGINT 自增字段。

主要关系包括：

- `wc_portal_member.team_id` 外键关联 `wc_portal_team.id`，实现团队与成员的一对多关系。
- `wc_portal_reply.message_id` 外键关联 `wc_portal_message.id`，实现留言与回复的关联。
- 对 `wc_portal_reply.message_id` 建立唯一索引，保证一条留言最多对应一条管理员回复。

此外，根据实际查询需要，为成员排序、留言状态及资料分类等字段建立索引。

### 3. Migration 执行与验证

通过 Yii2 Migration 执行数据库建表，并使用 MariaDB 命令行检查实际结果。

主要验证命令：

```sql
SHOW TABLES;
DESCRIBE wc_portal_team;
SHOW CREATE TABLE wc_portal_member;
SHOW CREATE TABLE wc_portal_reply;
```

验证结果：

- 数据库共创建 7 张表，包括 5 张模块 A 业务表、`user` 表及 `migration` 表。
- 团队表字段、主键和数据类型符合设计。
- 成员表的团队外键正确建立。
- 回复表的留言外键及唯一索引正确建立。

本阶段完成了模块 A 的数据库结构实现。

## 五、阶段 2：ActiveRecord Model 开发

### 1. 模型实现

在 `common/models/` 中新增五个 ActiveRecord Model：

| Model | 对应数据表 |
|---|---|
| `PortalTeam.php` | `wc_portal_team` |
| `PortalMember.php` | `wc_portal_member` |
| `PortalMessage.php` | `wc_portal_message` |
| `PortalReply.php` | `wc_portal_reply` |
| `PortalAssignment.php` | `wc_portal_assignment` |

五个模型均继承 `yii\db\ActiveRecord`，实现 `tableName()`、`rules()` 和 `attributeLabels()`，分别负责数据表映射、字段验证和属性标签。

### 2. 模型关系

根据数据库外键实现 ActiveRecord 关联：

- `PortalTeam::getMembers()`：获取团队成员，按照 `sort_order`、`id` 升序排列。
- `PortalMember::getTeam()`：获取成员所属团队。
- `PortalMessage::getReply()`：获取留言对应的管理员回复。
- `PortalReply::getMessage()`：获取回复关联的留言。

`PortalAssignment` 作为独立资料模型，不增加无关的关联关系。

### 3. 字段验证与业务规则

模型验证规则与实际数据库字段保持一致，包括必填字段、字符串长度、整数类型及外键存在性校验。

留言模型定义待审核、已发布和隐藏三种状态，并通过 `SCENARIO_PUBLIC_CREATE` 限制访客能够批量赋值的字段，仅开放昵称和留言内容，避免访客直接修改审核状态。

课程资料模型区分团队资料与个人资料，并校验对应的相对路径，仅接受规定目录下的文件路径，拒绝绝对路径、UNC 路径及路径穿越等非法输入。

五个模型均通过 `TimestampBehavior` 自动维护 `created_at` 和 `updated_at`，不允许普通表单直接批量赋值时间戳字段。

### 4. 运行验收

首先使用 PHP 8.0.30 对五个 Model 进行语法检查，全部通过。

随后使用临时测试脚本进行 Model 运行验收，共执行 **32 项测试，32 PASS、0 FAIL**。

测试内容包括：

- 模型自动加载。
- 数据库表名映射。
- `find()->count()` 实际数据库查询。
- 模型关联关系。
- 公开留言场景与安全属性。
- 课程资料相对路径校验。
- 时间戳行为配置。

五个模型均成功查询对应数据库表。测试时五张业务表记录数均为 0。

测试过程没有执行数据库写入操作，验收完成后删除临时脚本。

至此，模块 A 的 Model 层开发及运行验收完成。

## 六、阶段 3：团队主页与成员管理

### 1. 前台功能

新增：

- `frontend/controllers/TeamController.php`
- `frontend/views/team/index.php`

前台通过 `PortalTeam`、`PortalMember` 查询数据库，展示团队名称、简介、Logo，以及成员姓名、学号、职责、个人介绍和头像。

成员按照 `sort_order`、`id` 升序排列。

当数据库中没有团队资料时，页面显示空状态提示，避免产生异常。

对于数据库中的可编辑文本，使用 HTML 转义处理；图片路径仅允许指向前台资源目录内合法、实际存在的本地文件。

### 2. 后台管理功能

新增：

- `backend/controllers/PortalTeamController.php`
- `backend/controllers/PortalMemberController.php`
- 对应的团队及成员管理视图。

团队管理支持资料初始化、查看和修改。创建团队资料时通过业务逻辑限制重复初始化。

成员管理支持列表、查看、新增、修改、排序及删除。

创建和更新操作使用 ActiveRecord 的 `load()`、`save()` 完成数据加载和验证；删除操作仅允许 POST，并保留 CSRF 保护。

### 3. 访问权限

后台 Controller 要求用户已经登录，并具有 `managePortal` 权限。

开发过程中发现，项目最初没有配置统一的 RBAC 授权机制，因此即使普通用户登录成功，也不能访问团队及成员管理功能。

此问题在下一阶段统一处理。

### 4. 验证结果

本阶段新增 3 个 Controller 和 11 个 View，合计 14 个 PHP 文件，全部通过语法检查。

前台在数据库为空时通过实际 HTTP 验证，返回状态码 200，能够正常显示空状态页面。

游客访问后台管理路由时会被重定向至登录页面，未授权用户无法直接进入管理操作。

随后成功通过后台初始化团队资料，并进入成员创建页面。当前已录入本人资料，其他成员信息计划在后续集成阶段补充。

成员修改、删除及排序等操作仍需进一步完成实际验收。

## 七、阶段 3.5：Yii2 RBAC 管理员权限配置

### 1. RBAC 接入

为解决后台管理员权限问题，在 `common/config/main.php` 中注册共享的 `yii\rbac\DbManager`。

新增：

`console/controllers/RbacController.php`

实现以下控制台命令：

- `rbac/init`：初始化角色及权限。
- `rbac/assign-admin`：向指定用户分配管理员角色。
- `rbac/check`：检查用户权限。

初始化和分配命令包含必要的存在性检查，避免重复创建角色及授权关系。

### 2. 数据库初始化

使用 Yii2 官方 RBAC Migration 创建四张权限管理表：

- `auth_rule`
- `auth_item`
- `auth_item_child`
- `auth_assignment`

四条官方 RBAC Migration 均执行成功。

随后执行：

```text
yii rbac/init
```

创建 `admin` 角色、`managePortal` 权限，并建立 `admin → managePortal` 的继承关系。

### 3. 管理员授权

在本地开发环境中，注册并激活测试账号 `tjr`。

通过控制台命令向该账号分配管理员角色：

```text
yii rbac/assign-admin 1 tjr
```

随后执行：

```text
yii rbac/check 1 managePortal
```

返回结果：

```text
ALLOW
```

使用该账号重新访问后台团队管理页面，成功进入管理界面，不再出现此前的 403 错误。

### 4. 实现说明

RBAC 接入没有修改 `User` 模型或原有登录机制，后台仍通过 `managePortal` 权限限制管理操作。

管理员授权属于本地数据库数据，没有在公共 Migration 中固定用户 ID。其他成员部署项目时，可根据各自的实际账号进行授权。

本阶段完成了后台管理权限的配置与实际访问验证。

## 八、阶段 4：访客留言板与管理员回复

### 1. 前台留言板

新增：

- `frontend/controllers/GuestbookController.php`
- `frontend/views/guestbook/index.php`

实现游客公开留言功能。

访客无需登录即可填写昵称和留言内容。提交时使用 `PortalMessage` 的公开创建场景，由服务器将留言状态设为待审核。

前台仅展示已发布的留言，并同时显示对应管理员回复。

留言按 `created_at`、`id` 倒序排列，每页显示 10 条。

提交成功后采用 POST/Redirect/GET 模式，避免刷新页面造成重复提交。

为减少重复留言，增加基于 Yii Cache 的基本限流机制，包括提交间隔、单位时间内的次数限制以及相同内容检查。

### 2. 后台审核与回复

新增：

- `backend/controllers/PortalGuestbookController.php`
- `backend/views/portal-guestbook/` 下的相关视图。

后台实现：

- 留言列表与状态筛选。
- 留言详情查看。
- 审核发布及隐藏。
- 管理员回复。
- 已有回复的修改。

后台操作要求用户已经登录并具有 `managePortal` 权限。

审核发布和隐藏操作限定为 POST；回复保存使用模型验证，`message_id` 和 `admin_user_id` 由服务器设置。

回复表通过数据库唯一索引限制同一留言只能存在一条回复记录。

### 3. 安全与输入处理

前台使用独立场景限制访客可提交字段，审核状态不能由客户端直接指定。

留言昵称、正文及管理员回复在展示时进行 HTML 转义，避免将用户输入作为 HTML 执行。

前后台表单保留 CSRF 保护，后台管理操作继续使用 RBAC 授权检查。

### 4. 浏览器与数据库验收

六个新增 PHP 文件全部通过语法检查。

随后在本地 XAMPP 环境中进行实际功能测试。

首先通过前台留言板提交测试记录：

- 留言昵称：测试访客
- 留言内容：测试留言

提交后，使用管理员账号进入后台，将该留言审核发布，并添加回复“感谢留言”。

刷新前台页面后，能够看到已发布留言及对应管理员回复。

通过 MariaDB 命令行进一步检查数据库：

**`wc_portal_message`：**

- 留言 ID：1
- 审核状态：1（已发布）

**`wc_portal_reply`：**

- 回复 ID：1
- 关联留言 ID：1
- 管理员用户 ID：1
- 回复内容：感谢留言

数据库记录与浏览器显示结果一致。

### 5. 阶段结果

本阶段完成并验证了以下核心业务流程：

```text
访客提交留言
    ↓
数据库保存为待审核
    ↓
管理员审核发布
    ↓
前台公开展示
    ↓
管理员添加回复
    ↓
前台同步显示回复
```

留言板核心业务流程已通过实际验证。

限流、重复提交、隐藏留言、修改已有回复及异常 HTTP 请求等边界情况，仍需在后续综合测试中进一步检查。

## 阶段 5：团队及个人作业管理与下载

### 1. 开发内容

本阶段基于 `PortalAssignment` 模型，实现团队及个人作业的前台展示、后台资料维护、文件上传、发布控制及安全下载。

前台新增 `AssignmentController` 和课程资料中心页面，根据发布状态查询数据库记录，分别展示团队作业和个人作业，并提供下载入口。

后台新增 `PortalAssignmentController`、`PortalAssignmentForm` 及相关视图，实现资料登记、文件上传、分类查询、资料编辑、发布、取消发布和删除等功能。

### 2. 文件管理

作业文件分别存储于项目的 `data/team/` 和 `data/personal/` 目录。

数据库通过相对路径保存文件位置，并使用项目路径别名解析实际文件，避免依赖开发机器上的绝对路径。

下载功能通过 Controller 检查资料发布状态、文件存在性及真实路径，拒绝目录越界和非法文件访问。

文件上传限制类型及大小，使用安全文件名保存。后台管理操作要求登录并具有 `managePortal` 权限。

### 3. 验证结果

本阶段新增的 10 个 PHP 文件均通过语法检查，路径与访问控制检查结果为 13 PASS、0 FAIL。

在本地 XAMPP 环境中进行了实际浏览器测试，验证了资料登记、前台展示、文件下载、取消发布及后台文件上传功能。

其中，团队资料测试文件能够从前台正常下载，下载内容与原文件一致。随后完成了取消发布及上传功能的人工测试。

阶段 5 的主要业务功能已完成。后续综合验收将进一步检查异常请求、文件安全边界、课程作业交付目录以及新环境部署还原流程。

## 阶段 6：综合验收、故障修复与文档整理

### 阶段 6.1：资料静态访问漏洞修复

综合审查确认：项目位于 Apache 可访问目录时，`data/` 及其子目录会被 Apache 作为静态资源直接返回。该访问路径不会进入 Yii2，也就不会执行 `AssignmentController` 中的发布状态、真实路径和权限检查；目录索引还可能暴露资料文件名和未来的数据库导出文件。

为此新增 `data/.htaccess`，使用以下 Apache 2.4 规则拒绝全部直接 HTTP 访问：

```apache
# Block all direct HTTP access to course files and database exports.
Options -Indexes
Require all denied
```

真实 HTTP GET 验证中，`data/`、`data/team/`、`data/personal/` 以及确实存在的课程资料文件均返回 403，目录列表不再可见。PHP 对 `data/team/`、`data/personal/` 的本地文件读写验证正常，说明 HTTP 拒绝规则没有改变 Windows 文件系统权限。

数据库权限恢复后，又通过浏览器验证了课程资料中心及已发布资料的 Controller 下载；正常下载未受 `.htaccess` 影响。未发布资料仍由 Controller 拒绝，静态 URL 仍返回 403。

### 阶段 6.2：数据库权限异常修复

MariaDB 服务当时正常监听 3307 端口，项目 DSN 也指向正确的业务数据库，但 `worldcup_user` 缺少 `worldcup2026_db` 的数据库级授权，因此 Yii2 连接返回 1044。

进一步检查发现 MariaDB 系统授权表 `mysql.db` 存在 Aria 校验错误。处理前先完整备份 `data-clean`，随后由数据库管理员执行系统表修复：`REPAIR TABLE mysql.db` 完成，`CHECK TABLE mysql.db` 返回 OK。修复过程中数据库级授权记录从 7 条变为 0 条，因此重新向项目账号授予了 `worldcup2026_db` 的 SELECT、INSERT、UPDATE、DELETE 权限。

修复后 Yii2 Migration 历史查询恢复正常，团队主页、留言板、课程资料中心以及已发布资料下载均经浏览器重新验证正常。由于系统授权表修复会影响同一 MariaDB 实例中的其他账号，其他项目原有的数据库级授权仍需要逐一核查；本项目没有把该故障修复过程作为新环境部署的固定步骤。

整个记录未包含数据库密码。

### 阶段 6.3：代码完善与文档整理

本轮为 `PortalTeam::logo_path` 和 `PortalMember::avatar_path` 增加统一的图片相对路径验证器。图片字段仍可为空；允许项目内的相对路径和常用 Web 图片扩展名，并统一 Windows 路径分隔符；拒绝 URL、盘符路径、UNC 路径、以 `/` 开头的绝对路径、`.`/`..` 路径段、查询字符串和非图片扩展名。该修改只校验后台保存的路径格式，没有增加图片上传功能，也没有要求文件在验证时已经存在。

课程资料上传后的数据库保存失败清理逻辑继续只处理本次新上传文件。若删除失败，会通过 Yii warning 记录不含原始路径的路径指纹和异常类型，避免静默忽略；编辑资料原有的旧文件不在该清理流程内。`saveAs()` 失败时也会进入相同的安全清理与错误处理流程。

同时完成模块 A 用户手册，并补齐部署文档中的环境、依赖、数据库恢复、RBAC、资料目录、上传限制、生产安全和故障排查说明。最终 `data/install.sql` 仍未生成，公共导航以及模块 B/C 集成仍留待团队最终集成阶段处理。

本轮实际执行的代码检查和只读测试结果记录在阶段 6.3 交付报告中；未执行数据库写入、Migration、权限变更或 Git 提交。

## 后续集成与交付事项

1. 与模块 B、C 统一前后台导航、页面布局和共享 RBAC 权限命名。
2. 完成三个模块的综合浏览器回归测试及异常请求测试。
3. 数据库结构稳定后生成并验证最终 `data/install.sql`，明确它与 Migration 两种恢复方式的选择关系。
4. 在一台干净的 Windows + XAMPP 环境中完成从代码、依赖、数据库、RBAC 到资料文件的全流程还原测试。
5. 核查 MariaDB 系统授权表修复后其他本地项目账号是否仍保留所需数据库级权限。

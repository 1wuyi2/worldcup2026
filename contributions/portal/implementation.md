# 模块 A 开发过程记录

## 阶段 0：项目环境检查

项目采用 Yii2 Advanced 2.0.55、PHP 8.0.30 和 MariaDB 数据库，使用 GitHub 进行版本管理。

本人的开发分支为 `portal`，主要负责团队门户与互动模块，包括团队介绍、成员管理、留言板和课程资料下载。

通过项目扫描确认 Yii2 Advanced 框架及 Composer 依赖完整，模块 A 尚未开始实现。

## 阶段 0.5：数据库环境准备

本阶段使用 XAMPP 启动 MariaDB 10.4.32，数据库服务端口为 3307。

创建独立数据库 `worldcup2026_db`，采用 utf8mb4 字符集。

最初 Yii2 连接数据库时出现 1044 权限错误，原因是原有数据库账户没有访问新数据库的权限。

随后创建项目专用数据库用户 `worldcup_user`，仅授予其对 `worldcup2026_db` 的访问权限，并修改本地 `common/config/main-local.php`。

最后使用临时 PHP 脚本加载 Yii2 控制台配置，执行只读 SQL 查询，成功得到：

- Database: worldcup2026_db
- Version: 10.4.32-MariaDB

至此，Yii2 与独立数据库的连接验证完成。

## 阶段 1：数据库 Migration 开发与验证

本阶段根据设计文档模块 A 的数据库设计，使用 Yii2 Migration 实现五张业务表的创建。

新增迁移文件：

`console/migrations/m261119_190100_create_portal_tables.php`

创建的数据表包括 `wc_portal_team`、`wc_portal_member`、`wc_portal_message`、`wc_portal_reply` 和 `wc_portal_assignment`。

其中，`wc_portal_member.team_id` 通过外键关联团队表，`wc_portal_reply.message_id` 通过外键关联留言表，并为回复表的 `message_id` 设置唯一索引，保证每条留言最多对应一条管理员回复。

数据库使用 InnoDB 存储引擎、utf8mb4 字符集，并设置了成员排序、留言审核状态及作业分类等查询所需的索引。

执行 Yii2 Migration 后，通过 MariaDB 命令行进行实际验证：

- `SHOW TABLES`：确认数据库中存在 7 张表，包括 5 张模块 A 业务表及 2 张框架基础表。
- `DESCRIBE wc_portal_team`：确认团队表字段、类型和主键设置正确。
- `SHOW CREATE TABLE wc_portal_member`：确认成员表外键有效。
- `SHOW CREATE TABLE wc_portal_reply`：确认留言回复外键及唯一索引有效。

验证结果表明五张业务表均已成功创建，结构符合设计要求。

本阶段完成了数据库表结构实现，尚未进行业务数据录入和 MVC 功能开发。

## 阶段 2：ActiveRecord Model 开发

### 1. 开发内容

本阶段在数据库 Migration 完成的基础上，使用 Yii2 ActiveRecord 实现模块 A 的数据模型层。

在 `common/models/` 目录下新增以下五个文件：

- `PortalTeam.php`：管理团队基本信息。
- `PortalMember.php`：管理团队成员资料。
- `PortalMessage.php`：管理访客留言。
- `PortalReply.php`：管理管理员回复。
- `PortalAssignment.php`：管理团队和个人课程资料。

五个模型均继承 `yii\db\ActiveRecord`，通过 `tableName()` 映射对应的数据表，并使用 `rules()` 实现字段验证、`attributeLabels()` 定义属性名称。

### 2. 主要实现

**（1）模型关联**

- `PortalTeam::getMembers()`：团队与成员的一对多关联，按照 `sort_order` 和 `id` 升序排列。
- `PortalMember::getTeam()`：成员关联所属团队。
- `PortalMessage::getReply()`：留言与回复的一对一关联。
- `PortalReply::getMessage()`：回复关联对应留言。

**（2）数据验证**

根据数据库表结构设置必填字段、字符串长度、整数类型及外键存在性验证。

留言模型定义待审核、已发布和隐藏三种状态，并通过 `SCENARIO_PUBLIC_CREATE` 限制公开留言表单能够批量赋值的字段，避免访客直接修改审核状态。

课程资料模型区分团队资料与个人资料，对文件相对路径进行验证，仅接受对应分类下的 `data/team/` 或 `data/personal/` 路径，拒绝绝对路径及路径穿越输入。

**（3）时间戳管理**

五个模型均使用 `TimestampBehavior` 自动维护 `created_at` 和 `updated_at`，不将这两个字段开放为普通表单的安全批量赋值属性。

### 3. 验证情况

使用 PHP 8.0.30 对五个 Model 文件执行语法检查，均未发现语法错误。

使用 `yii migrate/history` 检查数据库迁移历史，确认以下三条迁移均已执行：

- `m130524_201442_init`
- `m190124_110200_add_verification_token_column_to_user_table`
- `m261119_190100_create_portal_tables`

通过 `git status --short` 检查，本阶段新增文件仅包含五个 Model，未修改数据库 Migration、公共用户模型及其他业务模块。

当前已完成模型代码与基本静态检查，尚未进行实际数据库读写和模型关系的运行测试。

### 4. 阶段总结

本阶段完成了五张业务表到 PHP ActiveRecord 模型的映射，实现了基本字段验证、数据关联和时间戳维护，为后续团队主页、成员管理、留言审核及课程资料下载功能提供数据模型支持。

在完成五个 ActiveRecord Model 的代码开发后，进一步使用临时 PHP 测试脚本进行运行验收。

共执行 32 项测试，结果为 32 PASS、0 FAIL。

测试内容包括模型自动加载、数据库表名映射、实际数据库查询、模型关联关系、留言公开提交场景、课程资料路径校验以及时间戳行为。

五个模型均能够通过 `find()->count()` 正常访问对应的数据表，测试时各表业务记录数均为 0。

其中，公开留言场景仅允许批量赋值昵称和内容，无法通过批量赋值修改审核状态；课程资料模型能够拒绝绝对路径、UNC 路径、路径穿越及分类目录不匹配的输入。

整个测试过程未执行数据库写操作。验收结束后已删除临时脚本，Git 工作区保持干净。

本阶段的 Model 层运行验收通过，后续将进入 Controller 和 View 开发。

## 阶段 3：团队主页与成员管理

### 1. 开发内容

本阶段在已完成的 ActiveRecord Model 基础上，继续实现模块 A 的前台展示与后台管理功能。

新增 3 个 Controller 和 11 个 View 文件，形成团队信息和成员信息的基本 MVC 功能结构。

前台新增 `TeamController` 和 `team/index.php`，从数据库读取团队及成员信息，展示团队名称、简介、Logo、成员姓名、学号、职责、简介和头像。成员按照 `sort_order`、`id` 升序排列。当数据库不存在团队资料时，页面显示友好的空状态。

后台新增 `PortalTeamController`、`PortalMemberController` 及相应视图，实现团队资料初始化、查看、修改，以及成员列表、新增、查看、修改、排序和删除。

### 2. 实现细节

团队资料采用业务层单记录管理，已有团队资料时禁止通过普通创建流程重复初始化。

成员创建和修改通过 ActiveRecord 的 `load()`、`save()` 执行模型验证，成员删除限定为 POST 请求，并保留 CSRF 保护。

前台对用户可编辑文本进行 HTML 转义；Logo 和头像只接受前台资源目录内安全、真实存在的本地文件路径，拒绝外部 URL、绝对路径及路径穿越输入。

### 3. 管理员权限

检查现有项目配置后发现，当前尚未建立统一的管理员权限管理机制。

后台 Controller 使用登录状态与 `managePortal` 权限共同限制访问，未获得该权限的用户无法进行团队或成员管理。

当前后台代码已经完成，但正式管理操作仍依赖后续的管理员权限配置，需要与其他模块开发成员协调统一实现。

### 4. 验证情况

本阶段新增的 14 个 PHP 文件全部通过语法检查。

前台空数据库状态已通过实际 HTTP 验证，返回状态码 200，页面能够正常显示空状态，没有出现 PHP 错误。

游客访问后台模块时会跳转登录页。后台 CRUD 的完整业务流程仍需在建立管理员权限并录入真实团队数据后进一步验证。

本阶段未写入测试数据，未修改数据库结构、已有 Model、公共首页及其他模块代码。

### 5. 后续工作

下一步需要协调完成 `managePortal` 权限配置，并在授权管理员账号下验证团队资料初始化、成员新增、修改、排序与删除，以及前后台数据的一致性。

## 阶段 4：功能测试与集成

待完成。
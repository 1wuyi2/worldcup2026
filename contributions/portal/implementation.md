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

下一阶段将继续完成 Model 运行验证，并开发团队主页和成员管理的 Controller、View。

## 阶段 3：前后台功能开发

待完成。

## 阶段 4：功能测试与集成

待完成。
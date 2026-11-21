# 模块 A 部署说明

本文说明如何在 Windows + XAMPP 环境中部署 WorldCup2026，并恢复模块 A 的团队、成员、留言、课程资料和 RBAC 功能。文中的 `<XAMPP目录>`、`<项目目录>`、数据库账号和端口均应按目标电脑实际环境替换，不应照搬开发电脑的绝对路径。

## 1. 环境要求

课程项目已验证的基线环境：

- Windows 10/11。
- Apache 2.4。
- PHP 8.0.30。
- MariaDB 10.4.32。
- Composer 2.x。
- Yii2 Advanced 2.0.55，由 Composer 安装。

PHP 至少需要启用 PDO、pdo_mysql、mbstring、openssl、fileinfo、intl、curl、dom、xml、xmlwriter、zip 等项目依赖要求的扩展。可以执行以下命令检查：

```powershell
<XAMPP目录>\php\php.exe -v
<XAMPP目录>\php\php.exe -m
```

## 2. 项目目录

与模块 A 部署直接相关的目录如下：

```text
backend/                         后台应用和模块 A 管理页面
common/models/                   Portal ActiveRecord 模型
common/validators/               共享图片路径验证器
console/controllers/             RBAC 控制台命令
console/migrations/              普通业务 Migration
contributions/portal/            模块 A 文档
data/team/                       团队课程资料
data/personal/                   个人课程资料
data/.htaccess                   禁止资料被 Apache 静态访问
frontend/                        前台团队、留言和资料页面
vendor/                          Composer 依赖，不提交 Git
```

`frontend/runtime`、`backend/runtime`、`console/runtime` 以及两个应用的 `web/assets` 是运行时目录，需要由 PHP/Apache 进程写入，但其中生成内容不应提交 Git。

## 3. 获取代码和安装依赖

克隆或解压项目后，在项目根目录执行：

```powershell
composer install
```

如果 PHP 和 Composer没有加入 PATH，可按目标电脑实际位置执行：

```powershell
<XAMPP目录>\php\php.exe <Composer目录>\composer.phar install
```

必须使用仓库中的 `composer.lock`，不要为了安装方便执行 `composer update`，也不要使用 `--ignore-platform-reqs` 绕过环境要求。

安装后可检查：

```powershell
<XAMPP目录>\php\php.exe <Composer目录>\composer.phar validate --strict
<XAMPP目录>\php\php.exe <Composer目录>\composer.phar check-platform-reqs
```

## 4. 初始化 Yii2 Advanced

首次部署运行项目根目录的 `init.bat`：

```powershell
.\init.bat
```

- 本地开发选择 Development。
- 最终演示或正式部署优先选择 Production。
- 不要把生成的 `main-local.php`、`params-local.php`、cookieValidationKey 或数据库密码提交 Git。

初始化后确认以下本地文件存在：

```text
common/config/main-local.php
frontend/config/main-local.php
backend/config/main-local.php
console/config/main-local.php
```

## 5. 准备数据库和项目账号

先在 MariaDB 中创建独立数据库，示例：

```sql
CREATE DATABASE `worldcup2026_db`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
```

数据库管理员可创建仅用于本项目的账号。密码必须替换为本机安全值，不能写入仓库：

```sql
CREATE USER 'worldcup_user'@'127.0.0.1'
    IDENTIFIED BY '<本机数据库密码>';
```

如果数据库结构由管理员导入，网站日常运行只需要数据库级 CRUD 权限：

```sql
GRANT SELECT, INSERT, UPDATE, DELETE
ON `worldcup2026_db`.*
TO 'worldcup_user'@'127.0.0.1';
```

如果需要让该项目账号直接执行 Migration，还需要在部署期间授予数据库级的建表和索引权限，例如 `CREATE`、`ALTER`、`DROP`、`INDEX`、`REFERENCES`。这些权限仅限 `worldcup2026_db`，不要授予全局权限或 `GRANT OPTION`。结构初始化完成后可按团队安全约定收回不再需要的 DDL 权限。

不要重复创建同名账号。MariaDB 账号由“用户名 + Host”共同确定，Yii2 DSN 使用 `127.0.0.1` 时，应检查对应的 `'worldcup_user'@'127.0.0.1'` 授权。

## 6. 配置 Yii2 数据库连接

只在本机的 `common/config/main-local.php` 中配置数据库组件。示意结构如下，不能将真实密码复制到公共文档或 Git：

```php
'db' => [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;port=<MariaDB端口>;dbname=worldcup2026_db',
    'username' => 'worldcup_user',
    'password' => '<本机数据库密码>',
    'charset' => 'utf8mb4',
],
```

确认控制台、前台和后台合并的是同一份 common 本地配置。排错时不得打印完整配置数组。

## 7. 恢复数据库结构

项目支持两种恢复方式，部署时必须选择一种，不能无条件重复执行两套建表流程。

### 方式 A：运行 Migration

在尚未导入完整 `data/install.sql` 的干净数据库中，先运行普通 Migration：

```powershell
<XAMPP目录>\php\php.exe yii migrate --migrationPath=@console/migrations
```

该路径包含 Yii2 用户基础表及各模块已提交的业务 Migration。执行前应检查当前分支和 Migration 内容，避免在错误数据库运行。

随后安装 Yii2 官方 RBAC 表：

```powershell
<XAMPP目录>\php\php.exe yii migrate --migrationPath=@yii/rbac/migrations
```

可分别查看可用 Migration 与数据库历史，但两类 Migration 默认仍使用同一张 `migration` 历史表：

```powershell
<XAMPP目录>\php\php.exe yii migrate/history --migrationPath=@console/migrations
<XAMPP目录>\php\php.exe yii migrate/history --migrationPath=@yii/rbac/migrations
```

### 方式 B：导入最终 `data/install.sql`

当前开发阶段尚未生成最终 `data/install.sql`。三个模块完成并稳定后，团队会生成经过干净数据库还原验证的完整 SQL。

未来若该 SQL 已包含用户表、模块业务表、RBAC 表及必要基础数据，导入后不得再次无条件执行创建同一批表的 Migration。应先检查 SQL 内容和 `migration` 历史，再决定是否存在尚未包含的新 Migration。

不要同时把“导入完整 SQL”和“从零运行全部 Migration”当作固定步骤。

## 8. 初始化 RBAC 权限

项目在 `common/config/main.php` 中使用 Yii2 官方 `yii\rbac\DbManager`。完成 RBAC 表安装后执行：

```powershell
<XAMPP目录>\php\php.exe yii rbac/init
```

该命令以幂等方式创建或复用：

- `admin` 角色。
- `managePortal` 权限。
- `admin → managePortal` 继承关系。

命令不会清空其他模块角色，也不会自动向注册用户分配管理员权限。

## 9. 创建并授权管理员账号

先通过正常注册流程或团队约定方式创建用户，并确认用户状态为 ACTIVE。通过只读查询核对本机用户 ID、用户名和状态：

```sql
SELECT id, username, status FROM user ORDER BY id;
```

然后显式分配管理员角色：

```powershell
<XAMPP目录>\php\php.exe yii rbac/assign-admin <用户ID> <用户名>
```

命令会验证 ID、用户名、用户状态和角色是否存在，不应在 Migration 或源码中固定某台电脑的用户 ID。

只读检查权限：

```powershell
<XAMPP目录>\php\php.exe yii rbac/check <用户ID> managePortal
```

返回 `ALLOW` 表示用户拥有模块 A 管理权限；`DENY` 表示没有权限。普通用户和新注册用户不会自动获得 `admin`。

模块 B/C 可复用同一个 `admin` 角色，并添加各自的 `manageNews`、`manageFootball` 等权限。不得重复创建不同含义的 `admin`，也不得调用 `removeAll()` 或 `removeAllAssignments()`。

## 10. 课程资料目录

确保以下目录存在：

```text
data/team/
data/personal/
```

- 团队课程资料放在 `data/team/`。
- 个人课程资料放在 `data/personal/`。
- PHP/Apache 进程需要对这两个目录具有读取权限；使用后台上传时还需要写入权限。
- 数据库只保存以 `data/team/` 或 `data/personal/` 开头的项目相对路径。
- 不要把目录设置成允许执行 PHP 的公开上传目录。

## 11. 禁止 `data/` 静态 HTTP 访问

课程资料和未来的 `data/install.sql` 不能由 Apache直接返回。公开下载必须经过 Yii2 `AssignmentController`，由控制器检查发布状态和安全路径。

项目的 `data/.htaccess` 内容为：

```apache
# Block all direct HTTP access to course files and database exports.
Options -Indexes
Require all denied
```

`Require all denied` 拒绝浏览器直接请求目录和文件，`Options -Indexes` 额外关闭目录列表。它只限制 Apache HTTP 请求，不影响 PHP 使用本地文件路径读写资料。

不能只依赖 Yii2 Controller。如果 Apache 将项目根目录作为公开目录，针对 `/data/...` 的静态请求不会进入 Yii2，数据库发布状态、RBAC 和路径检查都不会执行。

### Apache 2.4 要求

必须确认项目所在目录允许读取 `.htaccess`。如果服务器设置了 `AllowOverride None`，应由服务器管理员在 VirtualHost 中为项目的实际 `data` 目录配置等效的 `Require all denied` 和 `Options -Indexes`，不要擅自放宽整个服务器。

正式部署推荐为前台和后台分别建立站点，将 DocumentRoot 分别指向：

```text
<项目目录>/frontend/web
<项目目录>/backend/web
```

不要将整个项目根目录作为公开 Web 根目录。

### 403 验证

对真实存在的目录和文件执行 GET：

```text
http://<主机>/<项目路径>/data/
http://<主机>/<项目路径>/data/team/
http://<主机>/<项目路径>/data/personal/
http://<主机>/<项目路径>/data/team/<真实文件名>
```

以上地址应返回 HTTP 403，且不得显示目录列表。不存在文件产生的 404 不能代替真实文件拒绝测试。随后还要通过 `assignment/download&id=<已发布资料ID>` 验证 Controller 下载仍然正常。

Nginx、IIS 或禁用 `.htaccess` 的环境必须配置等效规则。例如 Nginx 可在对应 server 块中使用：

```nginx
location ^~ /data/ {
    deny all;
}
```

## 12. 图片资源路径

团队 Logo 和成员头像使用相对于 `frontend/web` 的本地路径，例如：

```text
images/team/logo.png
images/members/member-1.jpg
```

图片文件属于前台静态源码，不放入课程资料目录。模型允许字段为空，接受 PNG、JPG、JPEG、GIF、WEBP，并拒绝 URL、绝对路径和目录穿越。本模块当前不提供图片上传。

## 13. 文件上传配置

后台课程资料上传的应用层上限为 20 MB。PHP 配置必须满足：

- `file_uploads = On`。
- `upload_max_filesize` 不小于需要上传的文件大小。
- `post_max_size` 应大于 `upload_max_filesize`。

修改 PHP 配置后需要按目标环境要求重启 Apache。项目允许 PDF、DOC、DOCX、PPT、PPTX、ZIP、TXT，并同时检查扩展名和 MIME 类型。

## 14. 启动和验证

启动 Apache 与 MariaDB 后，按实际站点地址检查：

### 前台

```text
team/index
guestbook/index
assignment/index
```

### 后台

```text
site/login
portal-team/index
portal-member/index
portal-guestbook/index
portal-assignment/index
```

至少验证：

1. 团队和成员页面正常显示。
2. 游客留言保存为待审核，审核后才公开。
3. 普通登录用户不能访问模块 A 后台。
4. 授权管理员可以维护团队、成员、留言和资料。
5. 已发布资料可通过 Controller 下载，未发布资料不可下载。
6. `data/` 和真实资料的静态 URL 返回 403。

当前公共导航仍待三个模块统一集成，部署验收可先使用用户手册中的完整 URL。

## 15. 关闭公开调试信息

最终演示或部署应使用 Production 初始化配置，并确认：

- `YII_DEBUG` 为 false。
- Gii 和 Debug 模块不向访客开放。
- 错误页面不输出调用栈、数据库 DSN 或服务器绝对路径。
- 本地配置、日志、runtime 和 Composer 文件不在公开 Web 根目录内。

Development 配置只用于本机开发，不应直接用于可被其他人访问的部署环境。

## 16. 常见故障排查

### Yii2 返回数据库 1044

先检查 DSN 的主机、端口、数据库名和账号 Host 是否一致，再使用管理员只读查询确认数据库存在和账号授权。正常的新环境只需要创建账号并授予项目数据库权限。

本项目开发机曾出现 `mysql.db` Aria 校验错误，导致数据库级授权异常。处理时先完整备份 MariaDB 数据目录，再由管理员针对实际错误执行 `CHECK TABLE` 和 `REPAIR TABLE`，随后重新授予项目账号 CRUD 权限。该过程是特定故障案例，不是新环境部署的固定步骤；没有出现相同系统表错误时，不要修复或改动 `mysql.db`。

修复系统授权表可能影响其他数据库账号。发生该故障后应逐一核对其他项目原有的数据库级授权。

### 后台返回 403

确认用户已登录、状态为 ACTIVE，并执行 `yii rbac/check <用户ID> managePortal`。不要通过移除 AccessControl 或把所有登录用户当作管理员来绕过。

### Migration 提示表已存在

检查是否已经导入包含相同表的完整 `data/install.sql`，或是否在另一条恢复流程中执行过 Migration。不要直接删除现有业务表，应先核对 `migration` 历史和 SQL 内容。

### 上传失败

检查 PHP 上传限制、`data/team` 与 `data/personal` 写入权限、文件扩展名和 MIME 类型。不要把 `data/` 改成可公开访问来解决上传问题。

### 静态资料 URL 不是 403

确认 `data/.htaccess` 存在、Apache 允许对应目录使用 `.htaccess`，并检查 Apache 错误日志。如果服务器不支持 `.htaccess`，需要配置服务器级等效拒绝规则。

### 页面显示详细异常和绝对路径

确认使用 Production 配置并关闭 `YII_DEBUG`。数据库故障不应向未登录访客展示详细调用栈。

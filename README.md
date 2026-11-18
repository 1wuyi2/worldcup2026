# worldcup2026

“2026 世界杯主题团队门户网站”是《互联网数据库开发》课程的三人团队课程设计项目，基于 Yii2 Advanced 架构。当前处于基础环境与协作规范整理阶段，业务功能、数据库结构和最终页面均为规划中，不能视为已经完成。

## 1. 开发环境

团队统一以以下版本为基线：

- Windows、VS Code、PowerShell
- PHP 8.0.30
- MariaDB 10.4.32，默认课程环境端口为 3307
- Composer 2.10.3
- Git 2.47.0
- Yii2 Advanced 2.0.55

PHP 可执行文件位于 XAMPP 目录时，可使用完整路径执行；建议每位成员将自己的 PHP 和 Composer 正确加入 PATH。当前 lock 已在 PHP 8.0.30、Composer 2.10.3 下验证，组员应执行 `composer install`，不要自行执行 `composer update`。详见 [依赖兼容说明](docs/dependency-compatibility.md)。

## 2. 计划功能

以下内容均处于规划中：

- 世界杯新闻与赛事信息展示
- 球队、球员和比赛数据管理
- 用户登录及个人功能
- 后台内容与数据维护
- 团队作业和个人作业成果展示

最终功能范围以课程要求、数据库设计和团队分工为准。

## 3. 项目目录

```text
backend/          后台 Yii 应用
common/           前后台共享配置、模型和邮件模板
console/          控制台命令与数据库 Migration
data/             课程提交数据及 SQL
  team/           团队作业材料
  personal/       个人作业材料
docs/             设计文档、协作说明和测试记录
environments/     Yii init 使用的 dev/prod 配置模板
frontend/         前台 Yii 应用
vendor/           Composer 依赖，不提交到 Git
```

`frontend/web/assets/` 和 `backend/web/assets/` 是运行时发布目录，不提交。项目自己的 CSS、JavaScript 和图片应放在应用源码目录或 `web/css`、`web/js`、`web/images` 等静态源码目录中。

## 4. 首次本地配置

1. 克隆项目后确认 PHP、MariaDB 和 Composer 版本。
2. 执行 `composer install`，严格按仓库中的 lock 文件安装依赖；不要自行更新依赖。
3. 运行 `init.bat` 并选择 Development 环境。
4. 编辑本机生成的 `common/config/main-local.php`：
   - 数据库主机：本机地址；
   - 端口：本机 MariaDB 端口；
   - 数据库名、用户名、密码：由成员自行创建和保存。
5. 确认 frontend/backend 的 `cookieValidationKey` 已由 Yii init 自动生成且非空。
6. 不要把任何实际 `*-local.php`、密码、Token 或 cookie key 提交到 Git。

`environments/dev` 和 `environments/prod` 是唯一的初始化模板来源。模板中的 `YOUR_DATABASE_*` 是占位符，必须在本地生成的配置中替换；cookie key 在模板中故意留空，由 `init` 为每台电脑生成唯一值。

## 5. 前后台访问入口

使用当前 XAMPP 目录映射时，通常可访问：

- 前台：`http://localhost/worldcup2026/frontend/web/`
- 后台：`http://localhost/worldcup2026/backend/web/`

如果成员配置了 Apache VirtualHost，应在个人环境说明中记录自己的域名，不要提交仅适用于个人电脑的 Apache 配置。

## 6. 数据库约定

开发阶段以 `console/migrations/` 中的 Migration 记录结构变更。新增或修改表结构时，应创建新的 Migration，不应修改其他成员已共享并执行的历史 Migration。

约定流程：

1. 在功能分支创建 Migration。
2. 在独立测试数据库验证向上和回滚逻辑。
3. 通过评审后合并到 `dev`。
4. 组员在备份个人数据后自行执行批准的 Migration。

老师要求的最终初始化脚本路径为 `data/install.sql`。数据库设计尚未完成，因此当前不生成虚假 SQL；待结构稳定后，从干净数据库导出可重复执行的结构及必要基础数据。该文件允许提交，其他 SQL 导出和数据库备份默认忽略。

## 7. Git 分支协作

- `main`：稳定、可演示版本，禁止直接开发。
- `dev`：团队集成分支。
- `feature/<模块>-<功能>`：功能开发分支。
- `fix/<问题>`：缺陷修复分支。
- `docs/<主题>`：文档调整分支。

推荐流程：

1. 从最新 `dev` 创建短期功能分支。
2. 小步提交，提交信息说明“做了什么”和“为什么”。
3. 推送后创建 Pull Request 合并到 `dev`。
4. 至少一名其他成员检查代码、Migration 和敏感信息。
5. 集成测试通过后，再由 `dev` 合并到 `main`。

不要建立长期个人分支，也不要在共享分支执行强制推送。

## 8. 敏感信息保护

禁止提交：

- `.env`；
- 实际 `main-local.php`、`params-local.php`、测试本地配置；
- 数据库密码、root 密码、Token、cookie key；
- `vendor/`、`runtime/` 和运行时 `web/assets/`；
- 日志、缓存、临时文件、数据库备份；
- 个人 IDE 和操作系统文件。

可提交的示例必须使用清晰占位符。若凭据曾经进入 Git 历史，应立即轮换凭据，删除工作区文件并不能消除历史泄露。

## 9. Docker 与 Vagrant

课程默认环境是 XAMPP + MariaDB；Docker 和 Vagrant 均为可选示例，不是当前项目的必需运行方式。

Docker Compose 中的数据库密码只从本地 `.env` 读取：

1. 复制 `.env.example` 为 `.env`。
2. 替换所有 `CHANGE_ME` 占位值。
3. 保持 `.env` 不被 Git 跟踪。

现有 Docker 示例使用 MySQL 5.7，与课程 MariaDB 10.4 环境不完全相同。启用前必须在隔离分支验证 SQL、字符集和 Migration 兼容性。Vagrant 示例中的 GitHub Token 同样只是占位符。

## 10. 依赖安装与部署

当前依赖已升级并锁定到 PHP 8.0、Composer 2 兼容组合。普通开发环境安装命令：

```powershell
composer install
```

如果 Composer 未加入 PATH，可按本机实际路径执行；本课程基准环境示例：

```powershell
D:\xampp\php\php.exe D:\tools\composer\composer.phar install
```

生产环境不需要测试和代码生成工具时使用：

```powershell
composer install --no-dev
```

已验证的关键版本包括 Yii 2.0.55、yii2-composer 2.0.11、Codeception 4.2.2 和 PHPUnit 9.6.39。`composer.json` 只允许 `yiisoft/yii2-composer` 插件，并通过 `config.platform.php` 把依赖求解基线固定为 PHP 8.0.30。

已通过：开发及 `--no-dev` 干净安装、`composer validate --strict`、`composer check-platform-reqs`、自动加载、Yii 控制台启动、项目 PHP 语法检查，以及不访问数据库的前后台首页 HTTP 冒烟测试。

未验证：业务数据库连接、Migration、数据库相关 Codeception 用例、登录及业务功能。不得把上述冒烟测试解释为完整验收。

当前仍有两个依赖维护警告：SwiftMailer 已废弃；Codeception 4 的 `codeception/phpunit-wrapper` 已废弃。它们未触发已知漏洞公告，但后续升级 PHP 和测试框架时应迁移。只有负责依赖维护的成员可在评审通过的专用分支执行受控更新，并必须同时提交 `composer.json` 与 `composer.lock`。

正式部署前至少检查：

- 生产环境使用 `init` 的 Production 模板；
- debug 和 Gii 在生产环境关闭；
- Web 根目录分别指向 frontend/backend 的 `web/`；
- 本地配置和 `.env` 不可公开访问；
- 数据库账号遵循最小权限；
- 运行目录可写，源码目录不可被 Web 任意写入；
- 执行备份、Migration 和基础访问测试。

## 11. 常见问题

### 命令行找不到 php 或 composer

检查 PATH，或先用本机实际安装路径调用 PHP。团队文档不得假设所有人的 XAMPP 安装盘符相同。

### 页面提示 cookieValidationKey 为空

重新运行正确环境的 `init`，或只在本地配置中生成新的随机 key。不要把真实 key 写入 environments 模板。

### 数据库连接失败

核对 MariaDB 服务、端口、数据库名和本地账号权限。不要为了排错把密码发到群聊或提交到仓库。

### assets 或 runtime 权限/缓存异常

可以清理本机生成内容，但不要删除目录中的 `.gitignore`，也不要把生成文件加入 Git。

### Composer 无法安装

先阅读 [依赖兼容方案](docs/dependency-compatibility.md)。不要使用 `--ignore-platform-reqs` 掩盖版本冲突，也不要擅自执行全量更新。

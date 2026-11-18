# Composer 依赖兼容与验证记录

验证日期：2026-11-18

本项目依赖已在 PHP 8.0.30、Composer 2.10.3（Plugin API 2.9.0）环境中通过隔离升级和正式项目复验。升级过程未使用 `--ignore-platform-reqs`、`--ignore-platform-req`、`--no-plugins`、`--no-audit` 或全量插件授权，也没有执行数据库 Migration。

## 版本结果

| 组件 | 升级前 | 升级后 |
| --- | --- | --- |
| Yii2 | 2.0.32 | 2.0.55 |
| yiisoft/yii2-composer | 2.0.8 | 2.0.11 |
| yiisoft/yii2-bootstrap | 2.0.10 | 2.0.11 |
| yiisoft/yii2-swiftmailer | 2.1.2 | 2.1.3 |
| yiisoft/yii2-debug | 2.1.13 | 2.1.28 |
| yiisoft/yii2-gii | 2.1.4 | 2.2.7 |
| Codeception | 4.0.2 | 4.2.2 |
| module-asserts | 1.1.1 | 1.3.1 |
| module-filesystem | 1.0.2 | 1.0.3 |
| module-yii2 | 1.0.1 | 1.1.5 |
| codeception/verify | 1.1.0 | 1.5.0 |
| PHPUnit | 8.5.2 | 9.6.39 |
| Symfony BrowserKit | 4.2.4 | 5.4.45 |

Codeception 保持在 4.x，是因为现有测试目录和 Yii2 module 1.1.x 与该系列兼容，而较新的模块主版本已经提高 PHP 最低要求。PHPUnit 9.6 是 PHP 8.0 可用的兼容线。

## composer.json 变更

- PHP 约束由 `>=5.6.0` 改为 `^8.0`。
- Yii2 约束改为 `~2.0.55`。
- Gii 改为 `~2.2.0`；旧 2.1.x 被 Composer 安全策略因已知公告阻断。
- Codeception 固定为 `4.2.2`。
- 更新 Codeception 模块约束，并显式要求 PHPUnit `^9.6`。
- BrowserKit 改为 `^5.4`。
- 设置 `config.platform.php = 8.0.30`，确保以后重新求解时仍以课程 PHP 基线为准。
- `allow-plugins` 仅允许 `yiisoft/yii2-composer`，没有通配符授权。

`composer.lock` 由 Composer 正常解析生成，没有手工修改版本号。正式项目的 `composer.json`、lock 和 vendor 来自同一验证结果。

## 主要执行命令

隔离副本中的核心命令包括：

```powershell
D:\xampp\php\php.exe D:\tools\composer\composer.phar update --with-all-dependencies
D:\xampp\php\php.exe D:\tools\composer\composer.phar install
D:\xampp\php\php.exe D:\tools\composer\composer.phar install --no-dev
D:\xampp\php\php.exe D:\tools\composer\composer.phar validate --strict
D:\xampp\php\php.exe D:\tools\composer\composer.phar check-platform-reqs
D:\xampp\php\php.exe D:\tools\composer\composer.phar audit --locked
```

首次更新发现 Yii2 Gii 2.1.x 受到安全公告影响，Composer 拒绝解析。没有忽略公告，而是将 Gii 升到 2.2.7 后重新求解。

网络下载期间曾出现 GitHub/Packagist TLS `curl error 35`，通过重试和降低并发下载数完成安装；没有更换不可信仓库或关闭安全策略。

## 已通过的验证

- 空 vendor 下执行包含开发依赖的普通 `composer install`：通过，安装 83 个包。
- 空 vendor 下执行 `composer install --no-dev`：通过，安装 19 个生产包。
- 生产安装确认不包含 Codeception、PHPUnit、Gii 和 Debug。
- `composer validate --strict`：通过。
- `composer check-platform-reqs`：通过。
- Composer Plugin API 2.9.0 与 yii2-composer 2.0.11：兼容。
- PSR 自动加载：通过。
- Yii 显式版本检查：2.0.55。
- Development 与 Production 配置下执行 `yii help`：通过。
- Codeception 4.2.2 启动及 common/frontend/backend Actor build：通过。
- 隔离副本项目 PHP 文件语法检查：139 个文件，0 个错误。
- 正式项目 PHP 文件语法检查：134 个文件，0 个错误。
- 隔离副本 frontend/backend 首页 HTTP 测试：均返回 200。
- 正式项目 frontend/backend 首页 HTTP 复验：均返回 200。
- 正式项目再次执行普通 `composer install`：无需安装、更新或删除，证明 vendor 与 lock 一致。
- 正式项目实际 local 配置哈希在替换前后保持不变。

HTTP 测试只访问不需要数据库的首页，没有连接或修改业务数据库。

## 安全审计与维护警告

`composer audit --locked` 返回“未发现安全漏洞公告”，同时报告：

- `swiftmailer/swiftmailer` 已废弃，建议未来迁移到 Symfony Mailer；
- `codeception/phpunit-wrapper` 已废弃，Codeception 4/PHP 8.0 兼容链暂时仍需要它。

审计过程中 Asset Packagist 曾出现 TLS 连接错误，Composer 提示部分包信息来自本地缓存，因此本次审计不能表述为完全无条件通过。没有关闭审计或忽略安全公告。

PHP 8.0 本身已经停止官方安全维护。课程环境可继续使用该固定版本，但对公网长期部署前应规划升级 PHP，并同步升级 Codeception、PHPUnit 和邮件组件。

## 环境要求检查

`requirements.php` 结果为 0 个错误、7 个警告。警告包括可选缓存扩展、图片验证码所需的 GD/ImageMagick，以及生产安全配置建议。当前页面启动不依赖这些可选项；若以后启用图片验证码，应安装 GD 或 ImageMagick 并重新验证。

## 尚未验证

- 实际 MariaDB 连接；
- 任何 Migration 的执行或回滚；
- 数据库相关 Codeception 测试和 fixtures；
- 登录、注册、邮件发送及未来业务功能；
- Docker 与 MariaDB 之间的行为差异；
- 完整浏览器端交互和并发/性能测试。

## 组员安装方式

克隆仓库后，先确认 PHP 与 Composer 版本，然后在项目根目录执行：

```powershell
composer install
```

Composer 未加入 PATH 时，按本机路径调用：

```powershell
D:\xampp\php\php.exe D:\tools\composer\composer.phar install
```

随后运行 `init.bat`，选择 Development，并只在本机生成的 `common/config/main-local.php` 中填写数据库信息。禁止执行 `composer update`，禁止提交 vendor、实际 local 配置、cookie key 或密码。

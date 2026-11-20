# 模块 C 本地部署与集成

在项目根目录按团队 README 配置 PHP、Composer、Yii Development 和独立数据库。以下命令以已经将团队 PHP 加入 PATH 为例。

```powershell
php yii migrate --interactive=0
php yii migrate --migrationPath=@yii/rbac/migrations --interactive=0
php yii football-access/init
php yii football-access/assign <已激活的本地用户名>
```

将最后一行的用户名换成你的本机账号。该命令只分配赛事管理权限。集成后，全站 `admin` 角色通过继承获得 `manageFootball`。如需给同一账号开通全部模块管理，沿用团队的统一管理员分配流程。

在独立测试数据库准备好后，可执行：

```powershell
php yii_test migrate --interactive=0
php vendor/bin/codecept run -c common unit models/FootballMatchTest.php
```

## 集成时确认

- 用共享集成分支验证所有业务迁移和 Yii2 官方 RBAC 迁移。官方 RBAC 迁移沿用模块 A 的命令及迁移历史，新环境执行一次即可。
- 依次初始化各模块权限，验证同一个测试管理员进入每个后台模块，以及普通账号受限。
- 提交模块路由给公共导航负责人。赛事公开入口为 `football/teams` 与 `match/index`，后台入口为 `football-team/index`。
- 集成验收通过后，从干净的集成库统一生成 `data/install.sql`，并在另一套空库验证导入及角色配置。

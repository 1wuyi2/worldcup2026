# 模块 A 部署说明

## Yii2 RBAC 权限系统

项目使用 Yii2 官方 `yii\rbac\DbManager` 保存角色、权限、继承关系和用户授权。共享配置位于 `common/config/main.php`，前台、后台和控制台使用同一套 RBAC 数据。

后台团队与成员管理要求用户已登录，并拥有 `managePortal` 权限。普通用户和新注册用户不会自动获得任何管理员角色。

## 安装官方 RBAC 数据表

确认本地数据库配置指向当前项目的独立数据库后，在项目根目录执行：

```powershell
D:\xampp\php\php.exe yii migrate --migrationPath=@yii/rbac/migrations
```

该命令使用 `vendor/yiisoft/yii2/rbac/migrations` 中与当前 Yii2 版本配套的官方 Migration，创建以下表：

- `auth_rule`
- `auth_item`
- `auth_item_child`
- `auth_assignment`

普通业务 Migration 和 RBAC Migration 使用不同的迁移路径。可以分别检查迁移历史：

```powershell
D:\xampp\php\php.exe yii migrate/history --migrationPath=@console/migrations
D:\xampp\php\php.exe yii migrate/history --migrationPath=@yii/rbac/migrations
```

不要复制或修改 vendor 中的官方 RBAC Migration，也不要执行清空全部角色或授权的命令。

## 初始化角色和权限

安装 RBAC 表后执行：

```powershell
D:\xampp\php\php.exe yii rbac/init
```

该命令以幂等方式创建或复用：

- `admin` 角色
- `managePortal` 权限
- `admin → managePortal` 继承关系

重复执行不会创建重复数据，也不会删除已有角色、权限或授权。

## 分配本地管理员

先通过数据库管理工具执行只读查询，核对本地账号的 ID、用户名和状态：

```sql
SELECT id, username, status FROM user ORDER BY id;
```

确认账号信息后显式执行：

```powershell
D:\xampp\php\php.exe yii rbac/assign-admin <用户ID> <用户名>
```

示例中的参数必须替换为当前开发环境的真实值。命令会验证：

1. 用户 ID 是正整数。
2. 用户记录存在。
3. 数据库用户名与命令参数完全一致。
4. 用户状态为 `ACTIVE`。
5. `admin` 角色已经存在。
6. 用户尚未获得该角色。

管理员分配属于各开发环境的数据库状态，不应把某个固定用户 ID 写入 Migration，也不能在注册流程中自动授予管理员角色。

## 只读检查权限

执行以下命令检查某个用户是否拥有指定权限：

```powershell
D:\xampp\php\php.exe yii rbac/check <用户ID> managePortal
```

输出含义：

- `ALLOW`：用户拥有权限，命令退出码为 0。
- `DENY`：用户没有权限，命令使用非零退出码。

`rbac/check` 只读取用户和授权数据，不创建或修改角色、权限及分配记录。

## 模块 B/C 复用约定

`admin` 是全站共享管理员角色。模块 B、模块 C 如需增加管理权限，应创建各自清晰命名的权限，例如 `manageNews`、`manageFootball`，再将权限作为子项添加到现有 `admin` 角色。

各模块不得重复创建不同含义的 `admin`，不得调用 `removeAll()`、`removeAllAssignments()`，也不得通过普通用户注册流程分配管理员角色。跨模块 RBAC 变更应由团队协调后提交。

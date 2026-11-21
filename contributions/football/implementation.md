# 模块 C 实现与测试记录

## 实现过程

1. 按设计文档的数据字典创建五张 `wc_football_*` 表，使用 InnoDB、utf8mb4、唯一约束和模块内外键。可选的 `result_note` 用于单独记录点球大战等补充赛果。
2. 在 `common/models/` 新增五个 ActiveRecord 模型，统一维护时间戳和资料来源。比分未核实时保留空值，比分与进球事件分别录入。
3. 在前台实现球队、按队筛选球员、场馆、赛程与比赛详情。开球时间以 UTC 存储，前台显示北京时间。详情按实际分钟和补时顺序显示进球。
4. 在后台实现五类记录的新增、编辑、列表和删除。后台使用 `manageFootball` 权限，沿用与模块 A 一致的 Yii2 RBAC 配置。比赛和进球修改时检查进球归属，并在相关写入时锁定比赛记录。

## 主要代码

| 功能 | 路径 |
| --- | --- |
| 建表与回滚 | `console/migrations/m261120_233000_create_football_tables.php` |
| 模型和关联 | `common/models/Football*.php` |
| 前台入口 | `frontend/controllers/FootballController.php`、`frontend/controllers/MatchController.php` |
| 后台管理 | `backend/controllers/FootballCrudController.php` 与五个业务控制器，公共视图位于 `backend/views/football-admin/` |
| 权限初始化 | `console/controllers/FootballAccessController.php` |
| 比赛和进球回归测试 | `common/tests/unit/models/FootballMatchTest.php` |

## 结果记录

验证环境为 PHP 8.0.30、Yii 2.0.55、MariaDB 10.4.32。本地演示使用标有“演示”的虚构数据。

| 检查 | 结果 |
| --- | --- |
| C 模块迁移 | 在独立测试库建表、回滚、再次建表均成功 |
| 后台录入及编辑 | 五类记录通过实际后台表单录入，球队编辑在前台同步显示 |
| 前台访问 | 球队、球员、场馆、赛程及比赛详情通过 HTTP 验证 |
| 数据规则 | 四个 Codeception 测试通过，共 33 个断言，覆盖未知比分、0 比 0、UTC 转换、非法对阵、负比分、进球归属和补时排序 |
| A 与 C 集成 | 本地试合并无 Git 冲突。空库创建 A、C 各 5 张业务表，官方 RBAC 表与两个模块权限初始化成功，同一管理员获得两个模块权限 |
| 后台保护 | 未登录访问转到登录页，GET 删除请求返回 405，仍被引用的球队不能删除 |

仓库原有 `LoginFormTest::testLoginCorrect` 在当前依赖中调用 `hasntKey()` 会报错。C 模块测试可单独执行。

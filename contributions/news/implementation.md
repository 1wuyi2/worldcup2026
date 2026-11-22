# 模块 B：新闻资讯实现说明

作者：XPC

日期：2026-11-22

## 实现范围

本模块对应需求文档 F06、B05，包含新闻分类、文章、标签及文章标签关联四张业务表。游客可以查看已发布文章，按标题、分类、标签组合筛选；管理员可以维护分类和标签，编辑文章并进行草稿、发布、撤稿操作。

本次沿用已有 Yii2 Advanced、Bootstrap 和登录体系。新闻页面提供模块内部导航，全站首页、公共菜单和视觉主题留待团队统一调整。

## 文件组织

| 层次 | 文件或目录 | 职责 |
| --- | --- | --- |
| 数据库 | `console/migrations/m261122_230000_create_news_tables.php` | 建表、索引、外键及回滚 |
| 数据模型 | `common/models/NewsCategory.php`、`NewsArticle.php`、`NewsTag.php`、`NewsArticleTag.php` | 数据校验、关系和公开范围 |
| 查询模型 | `common/models/NewsSearch.php` | 筛选参数校验和查询组合 |
| 保存表单 | `backend/models/NewsArticleForm.php` | 在事务内保存文章及标签集合 |
| 后台控制器 | `backend/controllers/NewsAdminController.php`、`NewsController.php`、`NewsCategoryController.php`、`NewsTagController.php` | 权限、文章生命周期与分类标签维护 |
| 后台视图 | `backend/views/news/`、`backend/views/news-taxonomy/` | 管理列表、编辑表单、详情 |
| 前台 | `frontend/controllers/NewsController.php`、`frontend/views/news/` | 公开列表、筛选、详情 |
| 权限命令 | `console/controllers/NewsAccessController.php` | 幂等初始化 `manageNews`，接入共享 `admin` |
| 自动化测试 | `common/tests/unit/models/NewsArticleTest.php` | 发布范围、标签保存与输入校验 |

## 数据表与关系

| 表 | 用途 | 约束 |
| --- | --- | --- |
| `wc_news_category` | 分类名称、排序、启停状态 | 名称唯一 |
| `wc_news_article` | 标题、摘要、正文、来源、状态与时间 | 分类外键；正文 MEDIUMTEXT |
| `wc_news_tag` | 标签名称与标识 | slug 唯一 |
| `wc_news_article_tag` | 文章与标签多对多关联 | 联合主键；两个外键 |

四张表使用 InnoDB 和 utf8mb4。文章的分类外键采用 RESTRICT，防止删除有文章引用的分类；关联表对文章、标签外键采用 ON DELETE CASCADE。删除文章不会删除分类或标签；删除标签不会删除文章。业务模型自动维护创建、更新时间，文章公开查询建立状态、发布时间与编号的组合索引。

## 主要规则

1. 文章状态：`0` 草稿、`1` 已发布、`2` 已撤稿。首次发布和撤稿后重新发布自动记录当前时间；编辑已发布文章保留原发布时间。网页表单不能直接覆盖发布时间。
2. 前台列表与详情共用 `NewsArticle::publishedQuery()`：文章已发布、分类启用、发布时间不晚于当前时间才可见。草稿、撤稿、停用分类中的文章，即使知道详情地址也返回 404。
3. 分类排序数值越小越靠前。停用分类隐藏其中全部公开文章；重新启用后，已发布文章恢复展示。停用分类可用于保存草稿，但不能发布文章。
4. 标签 slug 使用小写字母、数字和连接符。一篇文章可绑定多个标签，保存前去重并检查标签存在；全部取消勾选会清空关联。
5. `saveWithTags()` 在事务中保存文章、替换标签关联，编辑时锁定文章记录。校验失败不会局部保存标题或标签，数据库异常由事务回滚。
6. 前台默认每页 10 条，按发布时间和编号倒序；后台默认每页 20 条。筛选使用参数化查询，公开请求不能通过 `status` 参数扩大可见范围。
7. 新闻正文采用纯文本，保留换行；标题、摘要、正文、分类与标签均转义输出。来源链接仅允许 HTTP/HTTPS。正文最多 100 万字符，摘要最多 1 万字符，标题最多 200 字符。
8. 后台要求 `manageNews` 权限，复用现有 `admin` 角色。删除、发布和撤稿仅允许 POST，保留 Yii 默认 CSRF 校验。

## 验证记录

本地验证环境：PHP 8.0.30、Yii2 2.0.55、MariaDB 10.4.32，数据库监听本地 3307 端口。

- 迁移正向执行、回滚和重新执行通过，确认正文实际列类型为 MEDIUMTEXT。
- 权限初始化重复执行通过，现有 XPC 管理员继承 `manageNews`。
- Codeception：6 项测试、56 次断言通过。覆盖发布与撤稿、分类停用、多标签同步、无效标签不部分保存、级联删除、组合筛选和非法输入。
- 独立测试库真实 HTTP 检查：45 项通过，覆盖管理员和普通账号、匿名跳转、后台页面、分类标签维护、草稿及公开详情、POST 限制、CSRF、内容转义、GET 筛选、12 条记录分页、撤稿与重新发布、分类引用保护和删除行为。
- HTTP 测试使用临时账号与独立 18180/18181 端口，结束后停止测试服务并清除测试数据。网站账号密码和用户的 18080/18081 服务未调整。

HTTP 检查脚本保存在本地忽略的 `console/runtime/`，不作为正式部署入口。可重复执行的模型测试见部署说明。

## 团队后续集成

- 将本模块公开路由 `news/index` 和后台路由 `news/index`、`news-category/index`、`news-tag/index` 接入对应应用公共导航。
- 按设计文档整理 2–3 类、5–8 篇有实际来源的新闻，并建立标签关联。测试数据已清理，未自动录入新闻素材。
- 统一视觉、真实内容与三模块验收后，再从干净集成库生成最终 `data/install.sql`，同步课程报告和演示截图。

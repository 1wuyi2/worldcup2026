# 模块 A 用户手册

## 1. 功能范围

模块 A 负责团队门户与课程互动，提供以下功能：

- 前台展示团队介绍和成员资料。
- 游客提交留言，查看审核通过的留言及管理员回复。
- 管理员维护团队和成员资料，审核、隐藏和回复留言。
- 管理员登记或上传团队作业、个人作业，并控制发布状态。
- 游客通过受控下载接口获取已发布的课程资料。

模块 A 使用 Yii2 后台登录和 `managePortal` 权限。普通注册用户不会自动获得管理权限。

## 2. 访问地址与路由

以下地址以项目直接放在 XAMPP `htdocs/worldcup2026` 下为例。若使用 VirtualHost，应将主机名和基础路径替换为实际配置。

### 前台

| 页面 | 路由 | 示例地址 |
| --- | --- | --- |
| 团队与成员 | `team/index` | `http://127.0.0.1/worldcup2026/frontend/web/index.php?r=team/index` |
| 留言板 | `guestbook/index` | `http://127.0.0.1/worldcup2026/frontend/web/index.php?r=guestbook/index` |
| 课程资料中心 | `assignment/index` | `http://127.0.0.1/worldcup2026/frontend/web/index.php?r=assignment/index` |

### 后台

| 页面 | 路由 | 示例地址 |
| --- | --- | --- |
| 后台登录 | `site/login` | `http://127.0.0.1/worldcup2026/backend/web/index.php?r=site/login` |
| 团队资料管理 | `portal-team/index` | `http://127.0.0.1/worldcup2026/backend/web/index.php?r=portal-team/index` |
| 成员管理 | `portal-member/index` | `http://127.0.0.1/worldcup2026/backend/web/index.php?r=portal-member/index` |
| 留言管理 | `portal-guestbook/index` | `http://127.0.0.1/worldcup2026/backend/web/index.php?r=portal-guestbook/index` |
| 课程资料管理 | `portal-assignment/index` | `http://127.0.0.1/worldcup2026/backend/web/index.php?r=portal-assignment/index` |

当前公共导航尚未完成三个模块的统一集成，暂时需要通过以上 URL 进入模块 A 页面。

## 3. 查看团队和成员资料

1. 打开前台 `team/index`。
2. 页面显示团队名称、团队介绍和有效的本地 Logo。
3. 成员按照 `sort_order`、`id` 升序排列，显示姓名、学号、职责、简介和有效头像。
4. 团队或成员尚未录入时，页面会显示空状态提示。

Logo 和头像填写相对于 `frontend/web` 的本地图片路径，例如：

```text
images/team/logo.png
images/members/member-1.jpg
```

允许 PNG、JPG、JPEG、GIF、WEBP。字段可以留空；不接受 URL、盘符绝对路径、UNC 路径或包含 `.`、`..` 的目录路径。本模块当前不提供图片上传，图片文件需要由项目维护者放入 `frontend/web` 下的适当源码目录。

## 4. 游客提交留言

1. 打开前台 `guestbook/index`。
2. 填写留言昵称和留言内容。
3. 点击“提交留言”。
4. 提交成功后页面提示留言需要审核。

新留言由服务器保存为“待审核”，游客不能自行设置审核状态。前台只展示“已发布”留言及其管理员回复。留言板带有基本提交间隔、时间窗口次数和重复内容限制；短时间重复提交可能收到限流提示。

## 5. 审核留言和管理员回复

1. 使用具有 `managePortal` 权限的账号登录后台。
2. 打开 `portal-guestbook/index`。
3. 可按待审核、已发布、已隐藏筛选留言。
4. 点击查看按钮进入详情。
5. 使用“审核发布”让留言显示在前台，或使用“隐藏”停止公开显示。
6. 点击“添加回复”填写管理员回复；已有回复时按钮显示为“修改回复”。

发布和隐藏操作使用 POST 请求并带有确认提示。一条留言最多对应一条管理员回复，修改回复不会创建第二条记录。

## 6. 管理团队资料

1. 打开后台 `portal-team/index`。
2. 如果尚无团队记录，点击“初始化团队资料”。
3. 填写团队名称、介绍和可选 Logo 路径后保存。
4. 已有团队记录时使用“修改团队资料”编辑。

系统按单团队模式管理。已有团队记录时再次进入创建流程会被引导到现有记录的修改页面。

## 7. 管理成员资料

1. 打开后台 `portal-member/index`。
2. 点击“新增成员”，填写所属团队、姓名、学号、职责、简介、头像路径和排序值。
3. 在列表或详情页进入修改页面更新资料。
4. `sort_order` 数值越小，前台显示越靠前；相同值再按成员 ID 升序排列。
5. 点击删除按钮并确认后删除成员。删除操作只接受 POST。

新增成员前必须先初始化团队资料。不存在的成员 ID 会返回 404。

## 8. 管理课程资料

打开后台 `portal-assignment/index` 后，可以按资料分类和发布状态筛选记录。每条记录包含标题、团队或个人分类、所属人、说明、相对路径和发布状态。

### 登记项目内已有文件

1. 先将文件放入对应目录：团队作业放在 `data/team/`，个人作业放在 `data/personal/`。
2. 点击“新增课程资料”。
3. 选择分类并填写标题、所属团队或成员和说明。
4. 在“已有文件相对路径”中填写项目相对路径，例如：

   ```text
   data/team/report.pdf
   data/personal/member-report.docx
   ```

5. 建议先保存为“未发布”，确认文件后再发布。

相对路径必须与分类目录一致，不接受绝对路径或上级目录。

### 通过网页上传

1. 在新增或修改表单中选择团队或个人分类。
2. 在“上传课程资料”中选择文件。
3. 保存后，系统使用随机安全文件名写入对应的 `data/team/` 或 `data/personal/`，并自动设置数据库相对路径。

允许的类型为 PDF、DOC、DOCX、PPT、PPTX、ZIP、TXT，应用层最大 20 MB，同时还受 PHP `upload_max_filesize` 和 `post_max_size` 限制。上传新文件会更新数据库路径，但不会自动删除旧文件。

“登记已有文件”不会复制文件；“网页上传”会把所选文件保存到项目资料目录。两种方式不要同时填写，选择上传文件时以上传结果为准。

## 9. 发布、取消发布和删除记录

- “发布”会先检查文件真实存在、分类目录正确且路径没有越界；检查失败时不会发布。
- “取消发布”后，资料立即从公开列表消失，Controller 下载接口也不再提供该记录。
- “删除记录”只删除数据库记录，不删除磁盘文件，避免误删课程作业。

发布、取消发布和删除均通过 POST 执行，并保留 Yii2 CSRF 防护。

## 10. 游客下载课程资料

1. 打开前台 `assignment/index`。
2. 在团队作业或个人作业区域找到已发布资料。
3. 点击“下载资料”。

下载请求使用 `assignment/download&id=<资料ID>`。Controller 会检查记录已发布、文件存在、分类与目录匹配，并确认真实文件没有通过符号链接越界。

不要使用 `/worldcup2026/data/...` 静态地址。项目的 `data/.htaccess` 会让 Apache 对此类请求返回 403，所有公开下载都必须经过 Yii2 Controller。

## 11. 管理员登录和权限

后台管理需要同时满足：

1. 已登录 Yii2 后台。
2. 当前用户拥有 `managePortal` 权限。

仅登录但没有该权限的用户会收到 403。管理员角色由控制台命令显式分配，注册新用户不会自动成为管理员。部署和授权方法见 `contributions/portal/deployment.md`。

## 12. 常见问题

### 页面出现数据库连接错误

检查 MariaDB 是否启动、端口和数据库名是否与本地 `common/config/main-local.php` 一致，并确认项目账号拥有目标数据库权限。不要把密码写入仓库或排错截图。

### 后台返回 403

确认账号已登录且拥有 `managePortal`。可使用部署文档中的 `yii rbac/check` 只读检查权限。

### Logo 或头像不显示

确认路径相对于 `frontend/web`，扩展名受支持，文件已经实际放入对应目录。路径字段不接受外部 URL。

### 上传提示文件过大或类型不允许

确认文件扩展名和 MIME 类型在白名单中，文件不超过应用层 20 MB，并检查 PHP 的 `upload_max_filesize`、`post_max_size`。

### 资料无法发布

检查数据库相对路径、团队/个人分类、磁盘文件是否一致。团队资料必须位于 `data/team/`，个人资料必须位于 `data/personal/`。

### 静态资料地址返回 403

这是正常安全策略。请从课程资料中心点击下载，不要直接访问 `data/`。

### 下载路由返回 404

记录可能未发布、已经删除、文件不存在或路径检查未通过。管理员应在后台核对记录和磁盘文件，不要通过关闭安全规则绕过检查。

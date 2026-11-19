<?php

/**
 * 模块 A 课程资料 ActiveRecord 模型。
 *
 * @author wuyi
 * @date 2026-11-19
 */

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class PortalAssignment extends ActiveRecord
{
    const CATEGORY_TEAM = 'team';
    const CATEGORY_PERSONAL = 'personal';

    const STATUS_UNPUBLISHED = 0;
    const STATUS_PUBLISHED = 1;

    public static function tableName()
    {
        return '{{%wc_portal_assignment}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function rules()
    {
        return [
            [['title', 'category', 'relative_path'], 'trim'],
            [['title', 'category', 'relative_path'], 'required'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 150],
            [['category'], 'string', 'max' => 20],
            [['owner_label'], 'string', 'max' => 60],
            [['relative_path'], 'string', 'max' => 255],
            [['category'], 'in', 'range' => [self::CATEGORY_TEAM, self::CATEGORY_PERSONAL]],
            [['status'], 'integer'],
            [['status'], 'default', 'value' => self::STATUS_UNPUBLISHED],
            [['status'], 'in', 'range' => [self::STATUS_UNPUBLISHED, self::STATUS_PUBLISHED]],
            [['relative_path'], 'validateRelativePath'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '资料编号',
            'title' => '文件标题',
            'category' => '资料分类',
            'owner_label' => '所属团队或个人',
            'relative_path' => '文件相对路径',
            'description' => '文件说明',
            'status' => '发布状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function validateRelativePath($attribute)
    {
        $path = (string) $this->$attribute;
        $normalizedPath = str_replace('\\', '/', $path);

        if (
            strpos($path, "\0") !== false
            || preg_match('/^[a-zA-Z]:[\\\\\/]/', $path)
            || strpos($normalizedPath, '/') === 0
            || preg_match('#(^|/)\.\.(/|$)#', $normalizedPath)
        ) {
            $this->addError($attribute, '文件路径必须是安全的项目相对路径，不能包含绝对路径或上级目录。');
            return;
        }

        $allowedPrefix = $this->category === self::CATEGORY_TEAM
            ? 'data/team/'
            : 'data/personal/';

        if (strpos($normalizedPath, $allowedPrefix) !== 0) {
            $this->addError($attribute, '文件路径必须位于当前分类对应的 data/team/ 或 data/personal/ 目录中。');
        }
    }

    public static function getCategoryOptions()
    {
        return [
            self::CATEGORY_TEAM => '团队作业',
            self::CATEGORY_PERSONAL => '个人作业',
        ];
    }

    public static function getStatusOptions()
    {
        return [
            self::STATUS_UNPUBLISHED => '未发布',
            self::STATUS_PUBLISHED => '已发布',
        ];
    }

    public function getCategoryLabel()
    {
        $options = self::getCategoryOptions();

        return isset($options[$this->category]) ? $options[$this->category] : '未知分类';
    }

    public function getStatusLabel()
    {
        $options = self::getStatusOptions();

        return isset($options[$this->status]) ? $options[$this->status] : '未知状态';
    }

    public function isPublished()
    {
        return (int) $this->status === self::STATUS_PUBLISHED;
    }
}

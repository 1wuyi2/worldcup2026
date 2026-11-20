<?php

/**
 * 模块 A 课程资料登记与文件上传表单模型。
 *
 * @author wuyi
 * @date 2026-11-20
 */

namespace backend\models;

use common\models\PortalAssignment;
use yii\base\Model;

class PortalAssignmentForm extends Model
{
    const MAX_FILE_SIZE = 20971520;

    public $title;
    public $category;
    public $owner_label;
    public $relative_path;
    public $description;
    public $status;
    public $uploadFile;

    /**
     * @param PortalAssignment|null $assignment
     * @param array $config
     */
    public function __construct(PortalAssignment $assignment = null, $config = [])
    {
        if ($assignment !== null) {
            $this->title = $assignment->title;
            $this->category = $assignment->category;
            $this->owner_label = $assignment->owner_label;
            $this->relative_path = $assignment->relative_path;
            $this->description = $assignment->description;
            $this->status = $assignment->status;
        } else {
            $this->category = PortalAssignment::CATEGORY_TEAM;
            $this->status = PortalAssignment::STATUS_UNPUBLISHED;
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['title', 'category', 'owner_label', 'relative_path'], 'trim'],
            [['title', 'category'], 'required'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 150],
            [['category'], 'string', 'max' => 20],
            [['owner_label'], 'string', 'max' => 60],
            [['relative_path'], 'string', 'max' => 255],
            [['category'], 'in', 'range' => [
                PortalAssignment::CATEGORY_TEAM,
                PortalAssignment::CATEGORY_PERSONAL,
            ]],
            [['status'], 'integer'],
            [['status'], 'in', 'range' => [
                PortalAssignment::STATUS_UNPUBLISHED,
                PortalAssignment::STATUS_PUBLISHED,
            ]],
            [['relative_path'], 'validateRelativePath'],
            [['uploadFile'], 'file',
                'skipOnEmpty' => true,
                'extensions' => ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'zip', 'txt'],
                'mimeTypes' => [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-powerpoint',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    'application/zip',
                    'application/x-zip-compressed',
                    'text/plain',
                ],
                'checkExtensionByMimeType' => true,
                'maxSize' => self::MAX_FILE_SIZE,
            ],
            [['uploadFile'], 'validateFileSource', 'skipOnEmpty' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => '文件标题',
            'category' => '资料分类',
            'owner_label' => '所属团队或个人',
            'relative_path' => '已有文件相对路径',
            'description' => '文件说明',
            'status' => '发布状态',
            'uploadFile' => '上传课程资料',
        ];
    }

    public function validateRelativePath($attribute)
    {
        if ($this->$attribute === null || $this->$attribute === '') {
            return;
        }

        $assignment = new PortalAssignment();
        $assignment->category = $this->category;
        $assignment->relative_path = $this->$attribute;
        $assignment->validate(['relative_path']);

        foreach ($assignment->getErrors('relative_path') as $error) {
            $this->addError($attribute, $error);
        }
    }

    public function validateFileSource($attribute)
    {
        if (($this->relative_path === null || $this->relative_path === '') && $this->uploadFile === null) {
            $this->addError($attribute, '请上传文件，或填写项目内已有文件的相对路径。');
        }
    }
}

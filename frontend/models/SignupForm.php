<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\db\IntegrityException;
use common\models\User;

/**
 * 前台用户注册表单。
 *
 * 注册成功后直接激活账号，不依赖邮件验证服务。
 * 新注册用户不自动获得任何后台管理权限。
 *
 * @author wuyi
 * @date 2026-11-27
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    /**
     * 注册数据验证规则。
     */
    public function rules()
{
    return [
        // 用户名验证
        ['username', 'trim'],
        [
            'username',
            'required',
            'message' => '请输入用户名。',
        ],
        [
            'username',
            'string',
            'min' => 2,
            'max' => 255,
            'tooShort' => '用户名至少需要2个字符。',
            'tooLong' => '用户名不能超过255个字符。',
        ],
        [
            'username',
            'unique',
            'targetClass' => User::className(),
            'message' => '该用户名已被使用。',
        ],

        // 邮箱验证
        ['email', 'trim'],
        [
            'email',
            'required',
            'message' => '请输入电子邮箱。',
        ],
        [
            'email',
            'string',
            'max' => 255,
            'tooLong' => '邮箱地址不能超过255个字符。',
        ],
        [
            'email',
            'email',
            'message' => '请输入有效的电子邮箱地址。',
        ],
        [
            'email',
            'unique',
            'targetClass' => User::className(),
            'message' => '该邮箱已被注册。',
        ],

        // 密码验证
        [
            'password',
            'required',
            'message' => '请设置密码。',
        ],
        [
            'password',
            'string',
            'min' => 6,
            'max' => 72,
            'tooShort' => '密码至少需要6个字符。',
            'tooLong' => '密码不能超过72个字符。',
        ],
        ['password', 'validatePasswordLength'],
    ];
}

    /**
     * 表单字段名称。
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'email' => '邮箱',
            'password' => '密码',
        ];
    }

    /**
     * 避免密码超出 bcrypt 的有效字节长度，
     * 同时拒绝包含空字节的密码。
     */
    public function validatePasswordLength($attribute, $params)
    {
        if ($this->hasErrors($attribute)) {
            return;
        }

        $password = (string) $this->$attribute;

        if (strlen($password) > 72) {
            $this->addError(
                $attribute,
                '密码不能超过72字节。'
            );
        }

        if (strpos($password, "\0") !== false) {
            $this->addError(
                $attribute,
                '密码包含非法字符。'
            );
        }
    }

    /**
     * 创建并直接激活普通用户账号。
     *
     * @return bool
     */
    public function signup()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User();

        $user->username = $this->username;
        $user->email = $this->email;

        // 使用 User 模型原有的安全密码哈希机制。
        $user->setPassword($this->password);

        // 保留身份认证所需的 AuthKey。
        $user->generateAuthKey();

        // 直接激活，不再等待邮件验证。
        $user->status = User::STATUS_ACTIVE;

        try {
            if ($user->save()) {
                return true;
            }

            Yii::warning(
                'User registration could not be saved.',
                __METHOD__
            );

        } catch (IntegrityException $e) {
            // 防止并发注册时绕过前置唯一性检查。
            Yii::warning(
                'User registration integrity conflict.',
                __METHOD__
            );

            $this->addError(
                'username',
                '注册失败，用户名或邮箱可能已被使用。'
            );

            return false;
        }

        $this->addError(
            'username',
            '账号创建失败，请稍后重试。'
        );

        return false;
    }
}
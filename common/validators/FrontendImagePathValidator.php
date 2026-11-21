<?php

/**
 * 模块 A 前台本地图片相对路径验证器。
 *
 * @author wuyi
 * @date 2026-11-21
 */

namespace common\validators;

use yii\validators\Validator;

class FrontendImagePathValidator extends Validator
{
    const ALLOWED_EXTENSIONS = ['png', 'jpg', 'jpeg', 'gif', 'webp'];

    public function validateAttribute($model, $attribute)
    {
        $path = $model->$attribute;
        if ($path === null || $path === '') {
            return;
        }

        if (!is_string($path)) {
            $this->addError($model, $attribute, '图片路径必须是 frontend/web 下的本地相对路径。');
            return;
        }

        $normalizedPath = str_replace('\\', '/', trim($path));
        $segments = explode('/', $normalizedPath);
        if (
            $normalizedPath === ''
            || preg_match('/[\x00-\x1F\x7F]/', $normalizedPath)
            || preg_match('/^[a-zA-Z]:\//', $normalizedPath)
            || strpos($normalizedPath, '/') === 0
            || strpos($normalizedPath, ':') !== false
            || strpos($normalizedPath, '?') !== false
            || strpos($normalizedPath, '#') !== false
            || in_array('', $segments, true)
            || in_array('.', $segments, true)
            || in_array('..', $segments, true)
        ) {
            $this->addError(
                $model,
                $attribute,
                '图片路径必须是 frontend/web 下的安全相对路径，不能使用 URL、绝对路径或上级目录。'
            );
            return;
        }

        $extension = strtolower(pathinfo($normalizedPath, PATHINFO_EXTENSION));
        if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            $this->addError($model, $attribute, '图片路径仅支持 PNG、JPG、JPEG、GIF 或 WEBP 文件。');
            return;
        }

        $model->$attribute = $normalizedPath;
    }
}

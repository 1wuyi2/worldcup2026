<?php

/**
 * 模块 A 课程资料文件路径解析与下载文件名处理组件。
 *
 * @author wuyi
 * @date 2026-11-20
 */

namespace common\components;

use common\models\PortalAssignment;
use Yii;

class PortalAssignmentFile
{
    /**
     * 解析并验证资料文件的真实路径。
     *
     * @param PortalAssignment $assignment
     * @return string|null
     */
    public static function resolve(PortalAssignment $assignment)
    {
        $relativePath = (string) $assignment->relative_path;
        $normalizedPath = str_replace('\\', '/', $relativePath);

        if (!self::isSafeRelativePath($normalizedPath, $assignment->category)) {
            return null;
        }

        $projectRoot = realpath(dirname(Yii::getAlias('@common')));
        if ($projectRoot === false) {
            return null;
        }

        $categoryDirectory = $assignment->category === PortalAssignment::CATEGORY_TEAM
            ? 'team'
            : 'personal';
        $allowedDirectory = realpath(
            $projectRoot . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $categoryDirectory
        );
        if ($allowedDirectory === false || !is_dir($allowedDirectory)) {
            return null;
        }

        $candidate = $projectRoot . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, $normalizedPath);
        $resolvedPath = realpath($candidate);
        if ($resolvedPath === false || !is_file($resolvedPath)) {
            return null;
        }

        $allowedPrefix = rtrim($allowedDirectory, '\\/') . DIRECTORY_SEPARATOR;
        $prefixMatches = DIRECTORY_SEPARATOR === '\\'
            ? strncasecmp($resolvedPath, $allowedPrefix, strlen($allowedPrefix)) === 0
            : strncmp($resolvedPath, $allowedPrefix, strlen($allowedPrefix)) === 0;
        if (!$prefixMatches) {
            return null;
        }

        return $resolvedPath;
    }

    /**
     * 生成适合 Content-Disposition 响应头的下载文件名。
     *
     * @param PortalAssignment $assignment
     * @param string $resolvedPath
     * @return string
     */
    public static function buildDownloadName(PortalAssignment $assignment, $resolvedPath)
    {
        $name = preg_replace('/[\x00-\x1F\x7F"\\\\\/]+/u', '_', (string) $assignment->title);
        $name = trim((string) $name, " .\t\n\r\0\x0B");
        if ($name === '') {
            $name = 'assignment-' . $assignment->id;
        }

        $extension = pathinfo($resolvedPath, PATHINFO_EXTENSION);
        $nameExtension = pathinfo($name, PATHINFO_EXTENSION);
        if ($extension !== '' && strcasecmp($nameExtension, $extension) !== 0) {
            $name .= '.' . strtolower($extension);
        }

        return $name;
    }

    /**
     * @param string $normalizedPath
     * @param string $category
     * @return bool
     */
    private static function isSafeRelativePath($normalizedPath, $category)
    {
        if (
            $normalizedPath === ''
            || strpos($normalizedPath, "\0") !== false
            || preg_match('/^[a-zA-Z]:\//', $normalizedPath)
            || strpos($normalizedPath, '/') === 0
            || strpos($normalizedPath, '//') === 0
            || preg_match('#(^|/)\.\.(/|$)#', $normalizedPath)
        ) {
            return false;
        }

        if ($category === PortalAssignment::CATEGORY_TEAM) {
            return strpos($normalizedPath, 'data/team/') === 0;
        }
        if ($category === PortalAssignment::CATEGORY_PERSONAL) {
            return strpos($normalizedPath, 'data/personal/') === 0;
        }

        return false;
    }
}

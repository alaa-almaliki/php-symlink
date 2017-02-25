<?php
namespace Symlink;

/**
 * Class FileList
 * @package Symlink
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
final class File
{
    /**
     * @param  string $path
     * @return array
     */
    public static function listFiles($path)
    {
        $files = [];

        if (!is_dir($path) || !file_exists($path)) {
            return [];
        }

        if (is_file($path)) {
            return [$path];
        }

        $excludes = [
            '.'.
            '..',
        ];

        foreach (scandir($path) as $file) {
            if (in_array($file, $excludes)) {
                continue;
            }

            if (is_file($file)) {
                $files[] = $file;
            }

            if (is_dir($file)) {
                $files = array_merge($files, self::listFiles($file));
            }
        }

        return $files;
    }
}
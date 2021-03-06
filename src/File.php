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
        $excludes = [
            '.',
            '..',
        ];

        if (!is_dir($path) || !file_exists($path)) {
            return [];
        }

        if (is_file($path)) {
            return [$path];
        }

        foreach (preg_grep('/^([^.])/', scandir($path)) as $filename) {
            if (in_array($filename, $excludes)) {
                continue;
            }

            $filePath = implode('/', [$path, $filename]);
            if (is_file($filePath) && !strpos($filePath, '~')) {
                $files[$filename] = $filePath;
            }

            if (is_dir($filePath)) {
                $files = array_merge($files, self::listFiles($filePath));
            }
        }

        return $files;
    }

    /**
     * @param $file
     * @return bool
     */
    public static function is777($file)
    {
        return decoct(fileperms($file) & 0777) == 777;
    }
}
<?php
namespace Symlink;

/**
 * Class Logger
 * @package Symlink
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
final class Logger
{
    const FILE_LINE_LIMIT = 10000;
    const LOG_DIR = 'var/log';

    /**
     * @param string $message
     * @param string $filename
     */
    public static function log($message, $filename = 'symlink.log')
    {
        if (!LOG_ENABLED) {
            return ;
        }
        $baseDir = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR;
        if (!is_dir($baseDir . DIRECTORY_SEPARATOR . self::LOG_DIR)) {
            foreach (explode('/', self::LOG_DIR) as $dir) {
                $baseDir .= $dir . '/';
                mkdir($baseDir);
                chmod($baseDir, 0777);
            }
        }

        $parts = [
            $baseDir,
            self::LOG_DIR,
            $filename
        ];
        $file = implode('/', $parts);

        self::_write($message, $file);
        if (!File::is777($file)) {
            chmod($file, 0777);
        }
        self::_clean($file);
    }

    /**
     * @param string $message
     * @param string $filename
     */
    private static function _write($message, $filename)
    {
        if (is_array($message)) {
            $message = print_r($message, true);
        }

        $handle = fopen($filename, 'a');
        $message = $message === PHP_EOL ? PHP_EOL : '[ ' . gmdate('YmdHis', time()) . ' ] ==> ' . $message . PHP_EOL;
        fwrite($handle, $message);
        fclose($handle);
    }

    /**
     * @param string $file
     */
    protected static function _clean($file)
    {
        $lines = 0;
        $handle = fopen($file, "r");
        while (!feof($handle)) {
            fgets($handle);
            $lines++;
        }

        if ($lines >= self::FILE_LINE_LIMIT) {
            file_put_contents($file, "");
        }

        fclose($handle);
    }

    /**
     * @return string
     */
    protected static function _getBaseDir()
    {
        return dirname(__FILE__);
    }
}
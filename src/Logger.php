<?php
namespace Symlink;

/**
 * Class Logger
 * @package Symlink
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
final class Logger
{
    const LOG_DIR = 'var/log';

    /**
     * @param string $message
     * @param string $filename
     */
    public static function log($message, $filename = 'symlink.log')
    {
        $logDir = '';
        if (!is_dir(self::LOG_DIR)) {
            foreach (explode('/', self::LOG_DIR) as $dir) {
                $logDir .= $dir . '/';
                mkdir($logDir);
                chmod($logDir, 0777);
            }
        }
        $file = self::LOG_DIR . DIRECTORY_SEPARATOR . $filename;
        self::_write($message, $file);
        if (!File::is777($file)) {
            chmod($file, 0777);
        }
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
        fwrite($handle, '[ ' . gmdate('YmdHis', time()) . ' ] ==> ' . $message . PHP_EOL);
        fclose($handle);
    }
}
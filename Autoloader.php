<?php

/**
 * Class BootStrap
 *
 * @author Alaa Al-Maliki<alaa.almaliki@gmail.com>
 */
final class Autoloader
{
    /** @var  Autoloader */
    private static $_instance;

    /**
     * @return Autoloader|static
     */
    private static function _instance()
    {
        if (!self::$_instance) {
            self::$_instance = new static();
        }
        return self::$_instance;
    }

    public static function register()
    {
        spl_autoload_register([self::_instance(), '_autoload']);
    }

    /**
     * @param string $class
     */
    private static function _autoload($class)
    {
        $classPath = explode('\\', $class);
        unset($classPath[0]);
        require_once __DIR__ . '/src' . DIRECTORY_SEPARATOR . implode('/', $classPath) . '.php';
    }
}
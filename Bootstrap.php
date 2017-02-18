<?php

final class BootStrap
{
    /** @var  BootStrap */
    protected static $_instance;

    /**
     * @return BootStrap|static
     */
    public static function instance()
    {
        if (!self::$_instance) {
            self::$_instance = new static();
        }
        return self::$_instance;
    }

    public static function register()
    {
        spl_autoload_register([self::instance(), 'autoload']);
    }

    /**
     * @param string $class
     */
    public static function autoload($class)
    {
        $classPath = explode('\\', $class);
        unset($classPath[0]);
        require_once __DIR__ . '/src' . DIRECTORY_SEPARATOR . implode('/', $classPath) . '.php';
    }
}
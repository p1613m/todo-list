<?php

namespace App\Core\Traits;

/**
 * Singleton Factory for models and DB
 */
trait MultiSingleton
{
    static array $instances = [];

    /**
     * Get instance
     *
     * @return self
     */
    static function getInstance(): self
    {
        $class = get_called_class();
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new $class();
        }

        return self::$instances[$class];
    }
}
<?php

namespace App\Core\Abstracts;

use App\Core\Interfaces\SingletonInterface;

/**
 * Singleton Factory for models and DB
 */
abstract class SingletonFactory implements SingletonInterface
{
    static ?array $instances = [];

    /**
     * Get instance
     *
     * @return static
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
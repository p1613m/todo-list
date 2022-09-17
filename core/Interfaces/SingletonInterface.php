<?php

namespace App\Core\Interfaces;

/**
 * Singleton interface for singleton factory
 */
interface SingletonInterface
{
    /**
     * @return static
     */
    static function getInstance(): self;
}
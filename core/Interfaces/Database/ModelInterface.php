<?php

namespace App\Core\Interfaces\Database;

use App\Core\Abstracts\Model;

/**
 * Model interface for base model abstract class
 */
interface ModelInterface
{
    /**
     * Call builder
     *
     * @return BuilderInterface
     */
    public static function query(): BuilderInterface;

    /**
     * Create new model
     *
     * @param array $attributes
     * @return mixed
     */
    public static function create(array $attributes): Model;

    /**
     * Get all attributes
     *
     * @return array
     */
    public function getAttributes(): array;

    /**
     * Update model with save
     *
     * @param array $attributes
     * @return bool
     */
    public function update(array $attributes): bool;

    /**
     * Save model
     *
     * @return bool
     */
    public function save(): bool;
}
<?php

namespace App\Core\Interfaces\Database;

use App\Core\Abstracts\Model;

/**
 * Builder interface for App\Core\Database\Builder
 */
interface BuilderInterface
{
    /**
     * Update select raw
     *
     * @param array $raw
     * @return BuilderInterface
     */
    public function select(array $raw): BuilderInterface;

    /**
     * Update where raw
     *
     * @param string $column
     * @param string $value
     * @return BuilderInterface
     */
    public function where(string $column, string $value): BuilderInterface;

    /**
     * Update limit raw
     *
     * @param int $count
     * @return BuilderInterface
     */
    public function limit(int $count): BuilderInterface;

    /**
     * Update offset raw
     *
     * @param int $number
     * @return BuilderInterface
     */
    public function offset(int $number): BuilderInterface;

    /**
     * Update order raw
     *
     * @param string $column
     * @param string $type
     * @return BuilderInterface
     */
    public function orderBy(string $column, string $type = 'desc'): BuilderInterface;

    /**
     * Get sql raw
     *
     * @return string
     */
    public function getSql(): string;

    /**
     * Find by id
     *
     * @param $id
     * @return Model|null
     */
    public function findById($id): ?Model;

    /**
     * Get all
     *
     * @return mixed
     */
    public function get(): array;

    /**
     * Get first
     *
     * @return Model|null
     */
    public function first(): ?Model;
}
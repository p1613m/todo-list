<?php

namespace App\Core\Database;

use App\Core\Abstracts\Model;
use App\Core\Abstracts\SingletonFactory;
use App\Core\Interfaces\Database\BuilderInterface;

/**
 * Builder for models query
 */
class Builder implements BuilderInterface
{
    protected SingletonFactory $model;
    protected array $whereConditions = [];
    protected string $limitRaw = '';
    protected string $offsetRaw = '';
    protected string $selectRaw = '*';
    protected string $orderRaw = '';

    /**
     * Init builder with model
     *
     * @param SingletonFactory $model
     */
    public function __construct(SingletonFactory $model)
    {
        $this->model = $model;
    }

    /**
     * Update select raw
     *
     * @param array $raw
     * @return BuilderInterface
     */
    public function select(array $raw): BuilderInterface
    {
        $this->selectRaw = implode(', ', $raw);

        return $this;
    }

    /**
     * Add where conditions
     *
     * @param string $column
     * @param string|int $value
     * @return BuilderInterface
     */
    public function where(string $column, string|int $value): BuilderInterface
    {
        $this->whereConditions[$column] = $value;

        return $this;
    }

    /**
     * Add limit to query
     *
     * @param int $count
     * @return BuilderInterface
     */
    public function limit(int $count): BuilderInterface
    {
        $this->limitRaw = " LIMIT $count";

        return $this;
    }

    /**
     * Add offset to query
     *
     * @param int $number
     * @return BuilderInterface
     */
    public function offset(int $number): BuilderInterface
    {
        $this->offsetRaw = " OFFSET $number";

        return $this;
    }

    /**
     * Order query
     *
     * @param string $column
     * @param string $type
     * @return BuilderInterface
     */
    public function orderBy(string $column, string $type = 'ASC'): BuilderInterface
    {
        $this->orderRaw = " ORDER BY $column $type";

        return $this;
    }

    /**
     * Prepare where condition from array to string
     *
     * @return string
     */
    private function prepareWhere(): string
    {
        $whereArray = [];
        if ($this->whereConditions) {
            foreach ($this->whereConditions as $column => $value) {
                $whereArray [] = "$column = :$column";
            }
        }

        return $whereArray ? " WHERE " . implode(' AND ', $whereArray) : "";
    }

    /**
     * Get all sql raw
     *
     * @return string
     */
    public function getSql(): string
    {
        return "SELECT {$this->selectRaw} FROM {$this->model->tableName}{$this->prepareWhere()}{$this->orderRaw}{$this->limitRaw}{$this->offsetRaw}";
    }

    /**
     * Prepare result
     *
     * @return SingletonFactory
     */
    private function prepareResult(): SingletonFactory
    {
        return DB::execute($this->getSql(), $this->whereConditions);
    }

    /**
     * Find row by id
     *
     * @param $id
     * @return Model|null
     */
    public function findById($id): ?Model
    {
        return $this->model::query()->where($this->model->primaryKey, $id)->first();
    }

    /**
     * Load data in model
     *
     * @param array|false $attributes
     * @return Model|null
     */
    public function load(array|false $attributes): ?Model
    {
        return $attributes ? (new ($this->model::class)())->load($attributes) : null;
    }

    /**
     * Load data to array of models
     *
     * @param array|null $rows
     * @return array
     */
    public function loadAll(?array $rows): array
    {
        $array = [];

        if ($rows) {
            foreach ($rows as $attributes) {
                $array [] = $this->load($attributes);
            }
        }

        return $array;
    }

    /**
     * Get total rows
     *
     * @return int
     */
    public function count(): int
    {
        $this->selectRaw = 'count(*)';

        return $this->prepareResult()->count();
    }

    /**
     * Get all rows
     *
     * @return array
     */
    public function get(): array
    {
        return $this->loadAll($this->prepareResult()->fetchAll());
    }

    /**
     * Get first row
     *
     * @return Model|null
     */
    public function first(): ?Model
    {
        return $this->load($this->prepareResult()->fetch());
    }
}
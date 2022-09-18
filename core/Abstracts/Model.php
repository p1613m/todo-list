<?php

namespace App\Core\Abstracts;

use App\Core\Database\Builder;
use App\Core\Database\DB;
use App\Core\Interfaces\Database\ModelInterface;
use App\Core\Interfaces\Database\BuilderInterface;
use App\Core\Traits\MultiSingleton;

/**
 * Abstract class for base model
 */
abstract class Model implements ModelInterface
{
    use MultiSingleton;

    public string $tableName;
    public array $fillable = [];
    public string $primaryKey = 'id';

    /**
     * Fill model attributes to public params
     */
    public function __construct()
    {
        $this->{$this->primaryKey} = null;

        foreach ($this->fillable as $column) {
            $this->{$column} = null;
        }
    }

    /**
     * Load model data to public params
     *
     * @param array $attributes
     * @return self
     */
    public function load(array $attributes): self
    {
        if(!$this->{$this->primaryKey}) {
            $this->{$this->primaryKey} = $attributes[$this->primaryKey] ?? null;
        }

        foreach ($this->fillable as $column) {
            if(isset($attributes[$column])) {
                $this->{$column} = $attributes[$column];
            }
        }

        return $this;
    }

    /**
     * Call query builder
     *
     * @return BuilderInterface
     */
    public static function query(): BuilderInterface
    {
        return new Builder(self::getInstance());
    }

    /**
     * Create a new model and send row to database
     *
     * @param array $attributes
     * @return mixed
     */
    public static function create(array $attributes): Model
    {
        $model = self::getInstance();
        $lastId = DB::insert($model->tableName, $model->fillable, $attributes);

        return $model->load($attributes + [$model->primaryKey => $lastId]);
    }

    /**
     * Get model attributes
     *
     * @return array
     */
    public function getAttributes(): array
    {
        $attributes = [];
        foreach ($this->fillable + [$this->primaryKey] as $column) {
            $attributes[$column] = $this->{$column};
        }

        return $attributes;
    }

    /**
     * Update model
     *
     * @param array $attributes
     * @return bool
     */
    public function update(array $attributes): bool
    {
        $this->load($attributes);

        return $this->save();
    }

    /**
     * Save model (for create new and update)
     *
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->{$this->primaryKey}) {
            $this->load($this::create($this->getAttributes())->getAttributes());
        } else {
            DB::update($this->tableName, $this->getAttributes(), $this->primaryKey, $this->{$this->primaryKey});
        }

        return true;
    }
}
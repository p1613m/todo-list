<?php

namespace App\Core\Database;

use App\Core\Abstracts\Model;
use App\Core\Abstracts\SingletonFactory;
use App\Core\Application;
use App\Core\Traits\MultiSingleton;
use PDO;
use PDOStatement;

/**
 * Database manage
 */
class DB
{
    use MultiSingleton;

    public PDO $pdo;
    public PDOStatement $statement;

    public function __construct()
    {
        $config = Application::$app->config;

        $this->pdo = new PDO('mysql:host=' . $config['DB_HOST'] . ';port=' . $config['DB_PORT'] . ';dbname=' . $config['DB_NAME'], $config['DB_USER'], $config['DB_PASSWORD']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    /**
     * Execute sql query with attributes
     *
     * @param string $sql
     * @param array $attributes
     * @return DB
     */
    public static function execute(string $sql, array $attributes): DB
    {
        $instance = self::getInstance();
        $instance->statement = $instance->pdo->prepare($sql);

        foreach ($attributes as $key => $value) {
            $instance->statement->bindValue(":$key", $value);
        }

        $instance->statement->execute();

        return $instance;
    }

    /**
     * Insert row by table name and columns with attrubutes
     *
     * @param string $tableName
     * @param array $columns
     * @param array $attributes
     * @return string|false
     */
    public static function insert(string $tableName, array $columns, array $attributes): string|false
    {
        $attributes = self::prepareAttributes($columns, $attributes);
        $columnsString = implode(', ', $columns);
        $attributesKeys = ':' . implode(', :', array_keys($attributes));

        $instance = self::execute("INSERT INTO `" . $tableName . "` ($columnsString) VALUES ($attributesKeys)", $attributes);

        return $instance->pdo->lastInsertId();
    }

    /**
     * Update row by table and primary key
     *
     * @param string $tableName
     * @param array $attributes
     * @param $primaryKey
     * @param $id
     * @return void
     */
    public static function update(string $tableName, array $attributes, $primaryKey, $id): void
    {
        $updateArray = [];
        foreach ($attributes as $column => $value) {
            $updateArray [] = "$column = :$column";
        }
        $updateRaw = implode(', ', $updateArray);

        self::execute("UPDATE `{$tableName}` SET $updateRaw WHERE {$primaryKey} = {$id}", $attributes);
    }

    /**
     * Prepare data for insert
     *
     * @param array $columns
     * @param array $attributes
     * @return array
     */
    private static function prepareAttributes(array $columns, array $attributes): array
    {
        $preparedAttributes = [];
        foreach ($columns as $column) {
            $preparedAttributes[$column] = $attributes[$column] ?? null;
        }

        return $preparedAttributes;
    }

    /**
     * Fetch all
     *
     * @return bool|array
     */
    public function fetchAll(): bool|array
    {
        return $this->statement->fetchAll();
    }

    /**
     * Fetch
     *
     * @return array|false
     */
    public function fetch(): array|false
    {
        return $this->statement->fetch();
    }

    /**
     * Get count rows
     *
     * @return int
     */
    public function count(): int
    {
        return $this->statement->fetchColumn();
    }
}